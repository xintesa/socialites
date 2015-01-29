<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');

use League\OAuth1\Client\Server\Twitter;

/**
 * SocialitesFacebookEventHandler
 */
class SocialitesTwitterEventHandler extends SocialitesBaseEventHandler
	implements CakeEventListener {

	protected $_sessionKey = 'Socialites.TwitterSession';

/**
 * implementedEvents
 */
	public function implementedEvents() {
		return array(
			'Socialites.oauthCallback' => array(
				'callable' => 'onCallback',
			),
			'Socialites.oauthAuthorize' => array(
				'callable' => 'onAuthorize',
			),
		);
	}

	protected function _sessionKey($name) {
		return $this->_sessionKey . '.' . $name;
	}

	protected function _transformConfig($config) {
		return array(
			'identifier' => $config['clientId'],
			'secret'  => $config['clientSecret'],
			'callback_uri' => $config['redirectUri'],
		);
	}

	public function getProviderClass() {
		return 'League\OAuth1\Client\Server\Twitter';
	}

	public function getProvider() {
		$config = $this->getConfig();
		$fqcn = $this->getProviderClass();
		$twitterConfig = $this->_transformConfig($config);
		if (!class_exists($fqcn)) {
			return null;
		}
		return new $fqcn($twitterConfig);
	}

	public function onCallback($event) {
		if (!$this->_isValidEvent($event)) {
			return;
		}

		$server = $this->getProvider();
		$controller = $event->subject;

		$oauthToken = $controller->request->query('oauth_token');
		$oauthVerifier = $controller->request->query('oauth_verifier');
		if (!$oauthToken || !$oauthVerifier) {
			throw new UnexpectedValueException('Missing token or verifier');
		}

		$tempCredentials = unserialize($controller->Session->read($this->_sessionKey));
		$controller->Session->delete($this->_sessionKey);
		$tokenCredentials = $server->getTokenCredentials(
			$tempCredentials, $oauthToken, $oauthVerifier
		);

		$oauthUser = $server->getUserDetails($tokenCredentials);

		$user = $this->_findLocalUser($oauthUser);

		if (empty($user)) {
			$provider = 'twitter';
			$userDefaults = $this->_getDefaults($oauthUser);
			$usersByEmail = array();
			$controller->Session->write('Socialites.newUser', compact(
				'provider', 'token', 'oauthUser', 'userDefaults', 'usersByEmail'
			));
			return $controller->redirect($this->_addUserUrl);
		}

		return compact('token', 'oauthUser', 'user');
	}

	public function onAuthorize($event) {
		if (!$this->_isValidEvent($event)) {
			return;
		}

		$server = $this->getProvider();
		$tempCredentials = $server->getTemporaryCredentials();
		$event->subject->Session->write($this->_sessionKey, serialize($tempCredentials));

		$server->authorize($tempCredentials);
	}

	protected function _transformDefaults($oauthUser, $defaults) {
		$website = Hash::extract($oauthUser->extra, 'entities.url.urls.{n}.expanded_url');
		if (!empty($website[0])) {
			$defaults['User']['website'] = $website[0];
		}
		return $defaults;
	}

}
