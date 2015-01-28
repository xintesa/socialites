<?php
if (!$this->Helpers->loaded('Socialites')):
	$this->Helpers->load('Socialites.Socialites');
endif;

?>
<p>
	<?= $this->Socialites->login('Github', array('provider' => 'github')) ?>
	<?= $this->Socialites->login('Facebook', array('provider' => 'facebook')) ?>
	<?= $this->Socialites->login('Google', array('provider' => 'google')) ?>
	<?= $this->Socialites->login('Twitter', array('provider' => 'twitter')) ?>
</p>

<p>
	<?= $this->Socialites->linkTo(null, array('provider' => 'github')) ?>
	<?= $this->Socialites->linkTo(null, array('provider' => 'facebook')) ?>
	<?= $this->Socialites->linkTo(null, array('provider' => 'google')) ?>
	<?= $this->Socialites->linkTo(null, array('provider' => 'twitter')) ?>
</p>

<p>
<?php
$url = array('plugin' => 'users', 'controller' => 'users');
if ($this->Session->read('Auth.User.id')):
	$user = $this->Session->read('Auth.User');
	echo 'You are logged in as: '. $user['username'] . '&nbsp;';
	echo $this->Html->link('Logout', $url + array('action' => 'logout'));
else:
	echo $this->Html->link('Login', $url + array('action' => 'login'));
endif;
?>
<p>