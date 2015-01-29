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
		);
	}

	public function onUsersLogout($event) {
		$event->subject->Session->delete('Socialites');
	}

}
