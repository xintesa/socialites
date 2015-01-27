<?php

$eventHandlers = array();

$eventHandlers[] = 'Socialites.SocialitesFacebookEventHandler';
$eventHandlers[] = 'Socialites.SocialitesTwitterEventHandler';
$eventHandlers[] = 'Socialites.SocialitesGithubEventHandler';

$config = array(
	'EventHandlers' => $eventHandlers,
);
