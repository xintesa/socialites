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
	}

	public function add() {
	}

}
