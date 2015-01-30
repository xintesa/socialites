<?php

App::uses('AppController', 'Controller');

class AuthenticationController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function callback() {
		$this->autoRender = false;

		$originalUser = $this->Auth->user();
		if ($originalUser) {
			$this->Session->write('Socialites.originalUser', $originalUser);
		}

		$eventName = 'Socialites.oauthCallback';
		$event = Croogo::dispatchEvent($eventName, $this);
	}

	public function login() {
		$this->autoRender = false;
		$eventName = 'Socialites.oauthAuthorize';
		$event = Croogo::dispatchEvent($eventName, $this);
	}

}
