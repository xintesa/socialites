<?php

Router::connect('/auth/:action/:provider/*', array(
	'plugin' => 'socialites',
	'controller' => 'authentication',
));

Router::connect('/auth/:action/*', array(
	'plugin' => 'socialites',
	'controller' => 'authentication',
));
