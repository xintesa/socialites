<?php

$scheme = 'https';
if (!defined('CROOGO_OAUTH_SERVER_URL') && isset($_SERVER['HTTP_HOST'])) {
	define('CROOGO_OAUTH_SERVER_URL', $scheme . '://' . $_SERVER['HTTP_HOST']);
}

$path = CakePlugin::path('Socialites');

Croogo::hookModelProperty('User', 'hasOne', array(
	'Socialite' => array(
		'className' => 'Socialites.Socialite',
		'dependent' => true,
	),
));

