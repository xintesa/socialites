<?php

$eventHandlers = array();

$eventHandlers[] = 'Socialites.SocialitesFacebookEventHandler';
$eventHandlers[] = 'Socialites.SocialitesTwitterEventHandler';

$config = array(
	'EventHandlers' => $eventHandlers,
);
