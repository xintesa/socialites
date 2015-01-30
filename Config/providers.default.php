<?php

$config = array(
	'Socialites' => array(
		'Providers' => array(
			'twitter' => array(
				'authorizeUri' => 'http://localhost/auth/login/twitter',
				'redirectUri' => '',
				'clientId' => '',
				'clientSecret' => '',
			),
			'facebook' => array(
				'redirectUri' => '',
				'clientId' => '',
				'clientSecret' => '',
			),
		),
	),
);
