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
			$this->_prepareUser($event, $oauthUser);
			return $event->subject->redirect($this->_addUserUrl);
		}

		return compact('token', 'oauthUser', 'user');
	}

}
