<?php

$scheme = 'https';
if (!defined('CROOGO_OAUTH_SERVER_URL') && isset($_SERVER['HTTP_HOST'])) {
	define('CROOGO_OAUTH_SERVER_URL', $scheme . '://' . $_SERVER['HTTP_HOST']);
}

$path = CakePlugin::path('Socialites');
if (file_exists($path . 'Vendor' . DS . 'autoload.php')) {
	require $path . 'Vendor' . DS . 'autoload.php';
}

Croogo::hookModelProperty('User', 'hasOne', array(
	'Socialite' => array(
		'className' => 'Socialites.Socialite',
		'dependent' => true,
	),
));

if (file_exists($path . 'Config' . DS . 'providers.php')) {
	Configure::load('Socialites.providers');
} else {
	CakeLog::critical('Socialites provider config not found');
}
