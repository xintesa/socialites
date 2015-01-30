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

}
