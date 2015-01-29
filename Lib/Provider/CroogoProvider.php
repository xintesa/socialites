<?php

App::uses('Configure', 'Core');

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Entity\User;

class CroogoProvider extends AbstractProvider {

	protected $_domain = null;

	public function __construct($options = array()) {
		if (empty($options['rootUrl'])) {
			throw new UnexpectedValueException('Missing rootUrl configuration');
		}
		parent::__construct($options);
		$this->_domain = rtrim($options['rootUrl'], '/');
	}

	public function urlAuthorize() {
		return $this->_domain . '/oauth/authorize';
	}

	public function urlAccessToken() {
		return $this->_domain . '/oauth/token';
	}

	public function urlUserDetails(AccessToken $token) {
		return $this->_domain . '/api/v1.0/users/me.json?access_token=' . $token;
	}

	public function userDetails($response, AccessToken $token) {
		$user = new User();
		$user->exchangeArray(array(
			'uid' => $response->id,
			'nickname' => $response->username,
			'name' => $response->name,
			'email' => $response->email,
			'timezone' => $response->timezone,
		));
		return $user;
	}

}
