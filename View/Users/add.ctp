<?php

echo $this->Form->create('User', array(
	'url' => array(
		'plugin' => 'socialites',
		'controller' => 'socialites_users',
		'action' => 'add',
	),
));

$defaultRoleId = 2;
$buttonTitle = __d('socialites', 'Create Account');
if (isset($this->data['User']['id'])):
	echo $this->Form->input('id');
	$defaultRoleId = $this->data['User']['role_id'];
	$buttonTitle = __d('socialites', 'Link Account');
endif;

echo $this->Form->input('role_id', array(
	'type' => 'hidden',
	'default' => $defaultRoleId,
));

echo $this->Form->input('email', array(
	'label' => __d('socialites', 'Email'),
));

echo $this->Form->input('username', array(
	'label' => __d('socialites', 'Username'),
));

echo $this->Form->input('name', array(
	'label' => __d('socialites', 'Name'),
));

echo $this->Form->input('website', array(
	'label' => __d('socialites', 'Website'),
));

echo $this->Form->input('bio', array(
	'label' => __d('socialites', 'Bio'),
));

echo $this->Socialites->providerUid();

echo $this->Form->submit(__d('socialite', $buttonTitle));

if (!empty($this->Form->validationErrors)):
	echo $this->Html->link(__d('socialites', 'Cancel'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
endif;

echo $this->Form->end();
