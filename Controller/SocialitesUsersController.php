<?php

App::uses('SocialitesAppController', 'Socialites.Controller');
App::uses('OpauthAuthenticate', 'Socialites.Controller/Component/Auth');

class SocialitesUsersController extends SocialitesAppController {

	public $name = 'Users';

	public $uses = 'Users.User';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->authenticate[] = 'Socialites.Opauth';
		$this->Auth->allow(array('add', 'login', 'me'));
	}

	public function login() {
		$this->autoRender = false;
		if ($this->Auth->user('id')) {
			$this->Session->setFlash(__d('socialites', 'You are already logged in'));
			return $this->redirect($this->Auth->loginRedirect);
		}

		if (isset($this->request->pass[0]) && $this->request->pass[0] == 'callback') {
			$sessionKey = OpauthAuthenticate::$sessionKey;
			if ($this->Session->check($sessionKey)) {
				$socialiteAuth = $this->Session->read($sessionKey);
				return $this->redirect(array('action' => 'add', strtolower($socialiteAuth['provider'])));
			}
		}

		if ($this->Auth->login()) {
			return $this->redirect($this->Auth->loginRedirect);
		} else {
			$redirect = $this->Auth->loginAction;

			$sessionKey = OpauthAuthenticate::$sessionKey;
			if ($this->Session->check($sessionKey)) {
				$this->Session->setFlash(__d('socialites', 'Confirm your %s account', Configure::read('Site.title')));
				$socialiteAuth = $this->Session->read($sessionKey);
				$redirect = array(
					'action' => 'add',
					strtolower($socialiteAuth['provider']),
				);
				return $this->redirect($redirect);
			} else {
				$this->Session->setFlash(__d('socialites', 'Incorrect username or password'));
			}
			return $this->redirect($redirect);
		}
	}

	protected function _getTwitterDefaults($data) {
		$defaults = array(
			'username' => $data['user']['screen_name'],
			'name' => $data['user']['name'],
			'website' => $data['user']['url'],
			'bio' => $data['user']['description'],
		);
		$this->request->data = array(
			'Socialite' => array(
				'twitter_uid' => $data['user']['id'],
			),
		);
		return $defaults;
	}

	protected function _setFormDefaults() {
		$strategy = null;
		$defaults = array(
			'username' => null,
			'email' => null,
			'name' => null,
			'website' => null,
			'bio' => null,
		);
		$sessionKey = OpauthAuthenticate::$sessionKey;
		if ($this->Session->check($sessionKey)) {
			$data = $this->Session->read($sessionKey);
			$this->Session->delete($sessionKey);

			$strategy = $data['provider'];
			$method = '_get' . $strategy . 'Defaults';
			if (method_exists($this, $method)) {
				$defaults = $this->$method($data);
			}

			$user = null;
			if (empty($user)) {
				$user = $this->User->findByUsername($defaults['username']);
				$this->_setupData($user);
			}
			if (empty($user)) {
				$user = $this->User->findByEmail($defaults['email']);
				$this->_setupData($user);
			}
		}
		$this->set(compact('defaults', 'strategy'));
	}

	protected function _setupData($user) {
		$this->request->data['User']['role_id'] = 2;

		if (empty($user)) {
			return;
		}

		$this->request->data['User']['id'] = $user['User']['id'];
		$this->request->data['Socialite']['user_id'] = $user['User']['id'];
	}

	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->saveAll($this->request->data)) {
				$this->Session->setFlash('Saved');
			} else {
				$this->Session->setFlash('Not Saved');
			}
		}
		$this->_setFormDefaults();
	}

}
