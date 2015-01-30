<?php

App::uses('CakeEventListener', 'Event');

class SocialitesSessionEventHandler implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'Controller.Users.afterLogout' => array(
				'callable' => 'onUsersLogout',
			),
			'Controller.Users.adminLogoutSuccessful' => array(
				'callable' => 'onUsersLogout',
			),
			'Controller.Users.beforeRegistration' => array(
				'callable' => 'onUsersBeforeRegistration',
			),
			'Controller.Users.registrationSuccessful' => array(
				'callable' => 'onUsersOAuthLogin',
			),
			'Controller.Users.loginSuccessful' => array(
				'callable' => 'onUsersOAuthLogin',
			),
		);
	}

/**
 * Delete all Socialites session data upon logout
 */
	public function onUsersLogout($event) {
		$event->subject->Session->delete('Socialites');
	}

/**
 * Preset required values
 */
	public function onUsersBeforeRegistration($event) {
		if (empty($event->subject->request->data['User'])) {
			$event->subject->request->data['User']['status'] = 1;
		}
	}

/**
 * Save tokens in session
 */
	public function onUsersOAuthLogin($event) {
		$controller = $event->subject;
		$Session = $controller->Session;
		if (!$Session || !$Session->check('Socialites.newUser.provider')) {
			return;
		}

		$provider = $Session->read('Socialites.newUser.provider');
		$token = $Session->read('Socialites.newUser.token');
		if ($provider && $token) {
			$oauthUser = $Session->read('Socialites.newUser.oauthUser');
			$Session->write('Socialites.identities.' . $provider, array(
				'uid' => $oauthUser->uid,
				'token' => (array)$token,
			));
		}
		$Session->delete('Socialites.newUser');
		$Session->delete('Socialites.originalUser');
	}

}
