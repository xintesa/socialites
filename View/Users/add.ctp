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

echo $this->Form->input('role_id', array('type' => 'hidden', 'default' => 2));

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

echo $this->Form->submit(__d('socialite', 'Create User'));

echo $this->Form->end();
