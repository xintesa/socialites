<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');
App::uses('CroogoProvider', 'Socialites.Provider');

/**
 * SocialitesCroogoEventHandler
 */
class SocialitesCroogoEventHandler extends SocialitesBaseEventHandler
	implements CakeEventListener {

/**
 * implementedEvents
 */
	public function implementedEvents() {
		return array(
			'Socialites.oauthCallback' => array(
				'callable' => 'onCallback',
			),
		);
	}

	public function getProviderClass() {
		return 'CroogoProvider';
	}

	public function getProvider() {
		$config = $this->getConfig();
		if ($config) {
			return new CroogoProvider($config);
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

		$user = $this->_findLocalUser($oauthUser);

		if (empty($user)) {
			$provider = $this->_providerId;
			$userDefaults = $this->_getDefaults($oauthUser);
			$usersByEmail = $this->_findUsersByEmail($oauthUser);
			$controller->Session->write('Socialites.newUser', compact(
				'provider', 'token', 'oauthUser', 'userDefaults', 'usersByEmail'
			));
			return $controller->redirect($this->_addUserUrl);
		}

		return compact('token', 'oauthUser', 'user');
	}

	protected function _findLocalUser($oauthUser) {
		$fieldName = $this->_providerId . '_uid';
		return $this->_Socialite->find('first', array(
			'conditions' => array(
				$fieldName => $oauthUser->uid,
			)
		));
	}

	protected function _findUsersByEmail($oauthUser) {
		$User = $this->_Socialite->User;
		$fieldName = $User->escapeField('email');
		$options = array(
			'fields' => array('User.*', 'Socialite.*'),
			'joins' => array(
				array(
					'type' => 'left',
					'table' => $User->useTable,
					'alias' => $User->alias,
					'conditions' =>
						'Socialite.user_id = ' . $User->escapeField(),
				),
			),
			'conditions' => array(
				$fieldName => $oauthUser->email,
			)
		);
		return $this->_Socialite->find('all', $options);
	}

}
