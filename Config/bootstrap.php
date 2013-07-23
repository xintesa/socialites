<?php

$scheme = 'https';
if (!defined('CROOGO_OAUTH_SERVER_URL') && isset($_SERVER['HTTP_HOST'])) {
	define('CROOGO_OAUTH_SERVER_URL', $scheme . '://' . $_SERVER['HTTP_HOST']);
}

$path = CakePlugin::path('Socialites');
require $path . 'Vendor/opauth/opauth/autoload.php';

$tmhOAuthPath = $path . 'Vendor/themattharris/tmhoauth/tmhOAuth.php';
if (file_exists($tmhOAuthPath)) {
	require $tmhOAuthPath;
	Opauth\AutoLoader::register('Opauth\\Strategy', $path . 'Vendor/opauth/twitter/Opauth/Strategy');
}

$facebookPath = $path . 'Vendor/opauth/facebook/Opauth/Strategy';
if (file_exists($facebookPath)) {
	Opauth\AutoLoader::register('Opauth\\Strategy', $facebookPath);
}

$googlePath = $path . 'Vendor/opauth/google/Opauth/Strategy';
if (file_exists($googlePath)) {
	Opauth\AutoLoader::register('Opauth\\Strategy', $googlePath);
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
	'Facebook' => array(
		'app_id' => '',
		'app_secret' => '',
	),
	'Google' => array(
		'client_id' => '',
		'client_secret' => '',
	),
	'Croogo' => array(
		'client_id' => '',
		'secret' => '',
	),
));
