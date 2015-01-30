<?php

class SocialitesBaseEventHandler extends Object {

	protected $_providerId = null;

	protected $_config = array();

	protected $_addUserUrl = array(
		'plugin' => 'socialites',
		'controller' => 'socialites_users',
		'action' => 'add',
	);

	protected $_Socialite = null;

	public function __construct($options = array()) {
		$this->_config = $options;
		$this->_providerId = $this->_providerId($options);
		Croogo::mergeConfig('SocialitesProviderRegistry', array(
			$this->_providerId => $this->getProviderClass()
		));
		$this->_Socialite = ClassRegistry::init('Socialites.Socialite');
		$this->_Socialite->recursive = -1;
		$this->_Socialite->contain('User');
	}

	protected function _providerId($options) {
		$providerId = strtolower(str_replace(
			array('Socialites', 'EventHandler'), '',
			get_class($this)
		));
		return empty($options['providerId']) ? $providerId : $options['providerId'];
	}

	public function getConfig() {
		$key = 'Socialites.Providers.' . $this->_providerId;
		if (Configure::check($key)) {
			return Configure::read($key);
		}
		return false;
	}

	public function getProviderClass() {
		$namespace = 'League\OAuth2\Client\Provider';
		$className = ucfirst($this->_providerId);
		return $namespace . '\\' . $className;
	}

	public function getProvider() {
		$fqcn = $this->getProviderClass();
		if (!class_exists($fqcn)) {
			return null;
		}
		$config = $this->getConfig();
		if ($config) {
			return new $fqcn($config);
		}
	}

	protected function _isValidEvent($event) {
		return $this->_providerId === $event->subject->request->param('provider');
	}

	protected function _getExistingUser() {
		return CakeSession::read('Socialites.originalUser');
	}

	protected function _transformDefaults($oauthUser, $defaults) {
		return $defaults;
	}

	protected function _getDefaults($oauthUser) {
		$fieldName = $this->_providerId . '_uid';
		$originalUser = $this->_getExistingUser();
		if ($originalUser) {
			$defaults = array(
				'User' => array_filter($originalUser, 'is_scalar'),
				'Socialite' => array(
					$fieldName => $oauthUser->uid,
				),
			);
		} else {
			$website = $oauthUser->urls;
			$website = is_array($website) ? current($website) : null;
			$defaults = array(
				'User' => array(
					'username' => $oauthUser->nickname,
					'name' => $oauthUser->name,
					'email' => $oauthUser->email,
					'website' => $website,
					'bio' => $oauthUser->description,
				),
				'Socialite' => array(
					$fieldName => $oauthUser->uid,
				),
			);
		}
		$defaults = $this->_transformDefaults($oauthUser, $defaults);
		return $defaults;
	}

	protected function _findLocalUser($oauthUser) {
		if (empty($oauthUser->uid)) {
			return array();
		}
		$field = $this->_Socialite->escapeField($this->_providerId . '_uid');
		return $this->_Socialite->find('first', array(
			'recursive' => -1,
			'contain' => 'User',
			'conditions' => array(
				$field => $oauthUser->uid,
			),
		));
	}

	protected function _findUsersByEmail($oauthUser) {
		if (empty($oauthUser->email)) {
			return array();
		}
		return $this->_Socialite->User->findByEmail($oauthUser->email);
	}

/**
 * Get relevant user data for storing in Socialites.newUser session data
 *
 * $options must contain the following variableS:
 *
 * - event The current event
 * - token User oauth token
 * - oauthUser User data returned from 3rd API
 */
	protected function _prepareUser($options) {
		$options = Hash::merge(array(
			'controller' => null,
			'token' => null,
			'oauthUser' => null,
		), $options);
		extract($options);
		$provider = $this->_providerId;
		$user = $this->_findLocalUser($oauthUser);
		$userDefaults = $this->_getDefaults($oauthUser);
		$usersByEmail = $this->_findUsersByEmail($oauthUser);
		$controller->Session->write('Socialites.newUser', compact(
			'provider', 'token', 'oauthUser', 'userDefaults', 'usersByEmail', 'user'
		));
		Croogo::dispatchEvent('Socialites.newUser');

		if (empty($user)) {
			return $controller->redirect($this->_addUserUrl);
		}
	}

	protected function _loginUser($controller) {
		Croogo::dispatchEvent('Controller.Users.beforeLogin', $controller);

		$user = $controller->Session->read('Socialites.newUser.user');
		if (!empty($user['User'])) {
			$user = $user['User'];
		}
		if (!empty($user) && $controller->Auth->login($user)) {
			Croogo::dispatchEvent('Controller.Users.loginSuccessful', $controller);
			return $controller->redirect($controller->Auth->redirectUrl());
		} else {
			Croogo::dispatchEvent('Controller.Users.loginFailure', $controller);
			return $controller->redirect($controller->Auth->loginAction);
		}

	}

	public function onCallback($event) {
		if (!$this->_isValidEvent($event)) {
			return;
		}

		$oauthClient = $this->getProvider();
		$controller = $event->subject;

		$token = $oauthClient->getAccessToken('authorization_code', array(
			'code' => $controller->request->query('code'),
		));

		$oauthUser = $oauthClient->getUserDetails($token);

		$this->_prepareUser(compact('controller', 'token', 'oauthUser'));

		$this->_loginUser($controller);
	}
}
