<?php

$eventHandlers[] = 'Socialites.SocialitesSessionEventHandler';

$eventHandlers[] = 'Socialites.SocialitesFacebookEventHandler';
$eventHandlers[] = 'Socialites.SocialitesTwitterEventHandler';
$eventHandlers[] = 'Socialites.SocialitesGithubEventHandler';
$eventHandlers[] = 'Socialites.SocialitesGoogleEventHandler';

$config = array(
	'EventHandlers' => $eventHandlers,
);
