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
		if (empty($event->result)) {
			throw new CakeException('Callback unsuccessful');
		}

		Croogo::dispatchEvent('Controller.Users.beforeLogin', $this);

		$user = array();
		if (!empty($event->result['user']['User'])) {
			$user = $event->result['user']['User'];
		}
		if (!empty($user) && $this->Auth->login($user)) {
			Croogo::dispatchEvent('Controller.Users.loginSuccessful', $this);
			return $this->redirect($this->Auth->redirect());
		} else {
			Croogo::dispatchEvent('Controller.Users.loginFailure', $this);
			return $this->redirect($this->Auth->loginAction);
		}
	}

	public function login() {
		$this->autoRender = false;
		$eventName = 'Socialites.oauthAuthorize';
		$event = Croogo::dispatchEvent($eventName, $this);
	}

}
