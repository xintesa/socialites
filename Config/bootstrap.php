<?php

$path = CakePlugin::path('Socialites');
require $path . 'Vendor/opauth/opauth/autoload.php';

$tmhOAuthPath = $path . 'Vendor/themattharris/tmhoauth/tmhOAuth.php';
if (file_exists($tmhOAuthPath)) {
	require $tmhOAuthPath;
	Opauth\AutoLoader::register('Opauth\\Strategy', $path . 'Vendor/opauth/twitter/Opauth/Strategy');
}

Opauth\AutoLoader::register('Opauth\\Strategy', $path . 'Opauth/Strategy');

Croogo::hookModelProperty('User', 'hasOne', array(
	'Socialite' => array(
		'className' => 'Socialites.Socialite',
	),
));

Configure::write('Opauth.Strategy', array(
	'Twitter' => array(
		'key' => '',
		'secret' => '',
	),
	'Croogo' => array(
		'client_id' => '',
		'secret' => '',
	),
));
