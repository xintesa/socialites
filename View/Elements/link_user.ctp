<?php

$originalUser = $this->Session->read('Socialites.originalUser');
$oauthUser = $this->Session->read('Socialites.newUser.oauthUser');

echo $this->Form->input('id', array('value' => $originalUser['id']));
?>
<table>
	<tr>
		<td>
			<em><?= Configure::read('Site.title') ?></em>
			<p>Username: <strong><?= $originalUser['username'] ?></strong></p>
			<p>Email: <?= $originalUser['email'] ?></p>
		</td>
		<td>
			<em><?= ucfirst($this->Session->read('Socialites.newUser.provider')) ?></em>
			<p>Username: <strong><?= $oauthUser->nickname ?></strong></p>
			<p>Email: <?= $oauthUser->email ?></p>
		<td>
		</td>
	</tr>
</table>
