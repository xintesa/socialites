<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');

use League\OAuth2\Client\Provider\Facebook;

/**
 * SocialitesFacebookEventHandler
 */
class SocialitesFacebookEventHandler extends SocialitesBaseEventHandler
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

	public function onCallback($event) {
		if (!$this->_isValidEvent($event, 'facebook')) {
			return;
		}

		$config = $this->getConfig();
		$oauthClient = $this->getProvider();
		$controller = $event->subject;

		$token = $oauthClient->getAccessToken('authorization_code', array(
			'code' => $controller->request->query['code'],
		));

		$oauthUser = $oauthClient->getUserDetails($token);

		$user = $this->_findLocalUser($oauthUser);

		if (empty($user)) {
			$provider = 'facebook';
			$userDefaults = $this->_getDefaults($oauthUser);
			$usersByEmail = $this->_findUsersByEmail($oauthUser);
			$controller->Session->write('Socialites.newUser', compact(
				'provider', 'token', 'oauthUser', 'userDefaults', 'usersByEmail'
			));
			Croogo::dispatchEvent('Socialites.newUser', $controller);
			return $event->subject->redirect($this->_addUserUrl);
		}

		return compact('token', 'oauthUser', 'user');
	}

	protected function _findLocalUser($oauthUser) {
		return $this->_Socialite->findByFacebookUid($oauthUser->uid);
	}

	protected function _findUsersByEmail($oauthUser) {
		if (empty($oauthUser->email)) {
			return null;
		}
		return $this->_Socialite->User->findByEmail($oauthUser->email);
	}

}
