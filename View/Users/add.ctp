<?php

echo $this->Form->create('User', array(
	'url' => array(
		'plugin' => 'socialites',
		'controller' => 'socialites_users',
		'action' => 'add',
	),
));

if (isset($this->data['User']['id'])):
	echo $this->Form->input('id');
endif;

echo $this->Form->input('username', array(
	'label' => __d('socialites', 'Username'),
	'default' => $defaults['username'],
));

echo $this->Form->input('name', array(
	'label' => __d('socialites', 'Name'),
	'default' => $defaults['name'],
));

echo $this->Form->input('website', array(
	'label' => __d('socialites', 'Website'),
	'default' => $defaults['website'],
));

echo $this->Form->input('bio', array(
	'label' => __d('socialites', 'Bio'),
	'default' => $defaults['bio'],
));

if ($strategy == 'Twitter'):
	echo $this->Form->input('Socialite.twitter_uid', array('type' => 'hidden'));
endif;

if ($strategy == 'Facebook'):
	echo $this->Form->input('Socialite.fb_uid', array('type' => 'hidden'));
endif;

if ($strategy == 'GitHub'):
	echo $this->Form->input('Socialite.github_uid', array('type' => 'hidden'));
endif;

if ($strategy == 'Google'):
	echo $this->Form->input('Socialite.google_uid', array('type' => 'hidden'));
endif;

if ($strategy == 'Croogo'):
	echo $this->Form->input('Socialite.user_id', array('type' => 'hidden'));
endif;

echo $this->Form->submit(__d('socialite', 'Create User'));

echo $this->Form->end();
