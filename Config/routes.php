<?php

Router::connect('/auth/associate/:provider/*', array(
	'plugin' => 'socialites',
	'controller' => 'socialites_users',
	'action' => 'associate',
));

Router::connect('/auth/:action/:provider/*', array(
	'plugin' => 'socialites',
	'controller' => 'authentication',
));

Router::connect('/auth/:action/*', array(
	'plugin' => 'socialites',
	'controller' => 'authentication',
));
