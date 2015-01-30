<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');

use League\OAuth2\Client\Provider\Github;

/**
 * SocialitesGithubEventHandler
 */
class SocialitesGithubEventHandler extends SocialitesBaseEventHandler
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
		if (!$this->_isValidEvent($event)) {
			return;
		}

		$oauthClient = $this->getProvider();
		$controller = $event->subject;

		$token = $oauthClient->getAccessToken('authorization_code', array(
			'code' => $controller->request->query['code'],
		));

		try {
			$oauthUser = $oauthClient->getUserDetails($token);
		} catch (Exception $e) {
			$this->log('getUserDetails() exception: ' . $e->getMessage());
			return;
		}

		try {
			$oauthUserEmails = $oauthClient->getUserEmails($token);
		} catch (Exception $e) {
			$this->log('getUserEmails() exception: ' . $e->getMessage());
			return;
		}

		$user = $this->_findLocalUser($oauthUser);
		$this->_prepareUser(compact('controller', 'token', 'oauthUser'));

		if (empty($user)) {
			return $controller->redirect($this->_addUserUrl);
		}

		return compact('token', 'oauthUser', 'user');
	}

	protected function _findUsersByEmail($oauthUserEmails) {
		$emails = array();
		foreach ($oauthUserEmails as $userEmail) {
			$emails[] = $userEmail->email;
		}
		return $this->_Socialite->User->find('all', array(
			'conditions' => array(
				$this->_Socialite->User->escapeField('email') => $emails,
			),
		));
	}

}
