<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class OpauthAuthenticate extends BaseAuthenticate {

	public $settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'username',
		),
		'recursive' => -1,
		'contain' => array(
			'Role', 'Socialite',
		),
	);

	public static $sessionKey = 'Socialites.Auth';

	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$config = Configure::read('Opauth');
		try {
			$Opauth = new Opauth\Opauth($config);
			$response = $Opauth->run();

		} catch (Exception $e) {
			CakeLog::critical('OpauthAuthenticate: ' . $e->getMessage());
			return false;
		}

		$query = array(
			'provider' => $response->provider,
			'user' => $response->raw['user'],
			'credentials' => $response->credentials,
			'info' => $response->info,
		);
		$user = $this->_findUser($query);
		if (!$user) {
			$this->_Collection->Session->write(self::$sessionKey, $query);
			return false;
		}
		return $user;
	}

	protected function _findUser($query, $password = null) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);
		$fields = $this->settings['fields'];

		$method = '_find' . $query['provider'] . 'User';
		if (method_exists($this, $method)) {
			$result = $this->$method($query);
		} else {
			return false;
		}

		if (empty($result) || empty($result[$model])) {
			return false;
		}
		$user = $result[$model];
        if (
			isset($conditions[$model . '.' . $fields['password']]) ||
			isset($conditions[$fields['password']])
		) {
			unset($user[$fields['password']]);
		}
		unset($result[$model]);
		return array_merge($user, $result);
	}

	protected function _findTwitterUser($query) {
		$uid = $query['user']['id_str'];
		$User = ClassRegistry::init($this->settings['userModel']);
		$user = $User->find('first', array(
			'conditions' => array(
				'SocialiteFilter.twitter_uid' => $uid,
			),
			'recursive' => $this->settings['recursive'],
			'contain' => $this->settings['contain'],
			'joins' => array(
				array(
					'table' => 'socialites',
					'alias' => 'SocialiteFilter',
					'conditions' => 'User.id = SocialiteFilter.user_id'
				),
			),
		));
		return $user;
	}

	protected function _findCroogoUser($query) {
		$uid = $query['user']['id'];
		$User = ClassRegistry::init($this->settings['userModel']);
		$user = $User->find('first', array(
			'conditions' => array(
				'User.id' => $uid,
			),
			'recursive' => $this->settings['recursive'],
			'contain' => $this->settings['contain'],
		));
		return $user;
	}

}
