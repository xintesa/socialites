<?php

$user = $this->Session->read('Auth.User');
$label = __('Associate my %s account', ucfirst($provider));

?>

You are currently logged in as:

<table>
	<tr>
		<th>Username</th>
		<td><?php echo $user['username']; ?></td>
	</tr>
	<tr>
		<th>Email</th>
		<td><?php echo $user['email']; ?></td>
	</tr>
</table>

<?php
	echo $this->Form->create();
	echo $this->Form->submit($label, array('name' => 'confirm_association'));
	echo $this->Form->end();
?>
