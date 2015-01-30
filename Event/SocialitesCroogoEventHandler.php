<?php

App::uses('CakeEventListener', 'Event');
App::uses('SocialitesBaseEventHandler', 'Socialites.Event');
App::uses('CroogoProvider', 'Socialites.Provider');

/**
 * SocialitesCroogoEventHandler
 */
class SocialitesCroogoEventHandler extends SocialitesBaseEventHandler
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

	public function getProviderClass() {
		return 'CroogoProvider';
	}

	public function getProvider() {
		$config = $this->getConfig();
		if ($config) {
			return new CroogoProvider($config);
		}
	}

}
