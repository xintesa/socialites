<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');

use League\OAuth2\Client\Provider\Google;

/**
 * SocialitesGoogleEventHandler
 */
class SocialitesGoogleEventHandler extends SocialitesBaseEventHandler
	implements CakeEventListener {

/**
 * implementedEvents
 */
	public function implementedEvents() {
		return array(
			'Socialites.oauthCallback' => array(
				'callable' => 'onCallback',
			),
		);
	}

}
