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
		if (!$this->Session->check('Socialites.originalUser')) {
			$this->request->data['User']['status'] = 1;
			if (empty($user['User']['role_id'])) {
				$this->request->data['User']['role_id'] = 2;
			} else {
				$this->request->data['User']['role_id'] = $user['User']['role_id'];
			}
		}
	}

	public function add() {
		$message = __d('socialites', 'Your %s account has been created', Configure::read('Site.title'));
		if ($this->Session->check('Socialites.originalUser')) {
			$message = __d('socialites', 'Your %s account has been linked', ucfirst($this->Session->read('Socialites.newUser.provider')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$saved = $this->User->saveAll($this->request->data);
			if ($saved) {
				$user = $this->User->findById($this->User->id);
				unset($user['User']['password']);
				if ($this->Auth->login($user['User'])) {
					$this->Session->setFlash($message, 'flash');
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

	public function associate() {
		$provider = $this->request->param('provider');
		if (empty($provider)) {
			throw new NotFoundException('Invalid provider');
		}
		$this->set(compact('provider'));

		if ($this->request->data('confirm_association')) {
			$this->User->recursive = -1;
			$originalUser = $this->User->findById($this->Auth->user('id'));
			$this->Session->write('Socialites.originalUser', $originalUser);
			switch ($provider) {
				case 'twitter':
					$eventName = 'Socialites.oauthAuthorize';
					$event = Croogo::dispatchEvent($eventName, $this);
					return $this->redirect();
				break;
				default:
					$config = Configure::read('Socialites.Providers.' . $provider);
					$fqcn = Configure::read('SocialitesProviderRegistry.' . $provider);
					$Provider = new $fqcn($config);

					return $this->redirect($Provider->getAuthorizationUrl());
				break;
			}
		} else {
			if (!$this->Auth->user('id')) {
				$this->Session->setFlash(__d('socialites', 'You are not logged in'), 'flash');
				return $this->redirect($this->referer());
			}
		}
	}

}
