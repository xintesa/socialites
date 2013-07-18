<?php

App::uses('SocialitesAppModel', 'Socialites.Model');

class Socialite extends SocialitesAppModel {

	public $primaryKey = 'user_id';

	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
		),
	);

}
