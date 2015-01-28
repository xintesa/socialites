<?php

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
