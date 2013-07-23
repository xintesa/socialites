<?php

Router::connect('/auth/:strategy/*', array(
	'plugin' => 'socialites',
	'controller' => 'socialites_users',
	'action' => 'login',
), array(
	'strategy' => '(twitter)|(facebook)|(google)|(croogo)',
));

Router::connect('/auth/:action/*', array(
	'plugin' => 'socialites',
	'controller' => 'socialites_users',
));
