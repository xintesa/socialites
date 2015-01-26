<?php

App::uses('SocialitesAppController', 'Socialites.Controller');

class SocialitesUsersController extends SocialitesAppController {

	public $name = 'Users';

	public $uses = 'Users.User';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

	protected function _setFormDefaults() {
		if ($this->Session->check('Socialites.newUser.userDefaults')) {
			$user = $this->Session->read('Socialites.newUser.userDefaults');
			$this->request->data = $user;
		}
		if (empty($user['User']['role_id'])) {
			$this->request->data['User']['role_id'] = 2;
		} else {
			$this->request->data['User']['role_id'] = $user['User']['role_id'];
		}
	}

	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['User']['status'] = 1;
			$saved = $this->User->saveAll($this->request->data);
			if ($saved) {
				$user = $this->User->findById($this->User->id);
				unset($user['User']['password']);
				if ($this->Auth->login($user['User'])) {
					$this->Session->setFlash(__d('socialites', 'Your %s account has been created', Configure::read('Site.title')), 'flash');
					$homeUrl = Configure::read('Site.home_url');
					if (empty($homeUrl)) {
						$homeUrl = '/';
					}
					return $this->redirect($homeUrl);
				}
			} else {
				$this->log($this->User->validationErrors);
				$this->Session->setFlash(__d('socialites', 'There was a problem creating your account'), 'flash');
			}
		}
		$this->_setFormDefaults();
	}

}
