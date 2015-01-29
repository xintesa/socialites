<?php

echo $this->Form->create('User', array(
	'url' => array(
		'plugin' => 'socialites',
		'controller' => 'socialites_users',
		'action' => 'add',
	),
));

if ($this->Session->check('Socialites.originalUser')):
	$buttonTitle = __d('socialites', 'Link Account');
	echo $this->element('Socialites.link_user');
else:
	$buttonTitle = __d('socialites', 'Create Account');
	echo $this->element('Socialites.new_user');
endif;

echo $this->Socialites->providerUid();

echo $this->Form->submit(__d('socialite', $buttonTitle));

if (!empty($this->Form->validationErrors)):
	echo $this->Html->link(__d('socialites', 'Cancel'), array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
endif;

echo $this->Form->end();
