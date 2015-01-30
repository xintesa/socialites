<?php

App::uses('AppHelper', 'View/Helper');

class SocialitesHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Form',
		'Session',
	);

	protected function _getProvider($provider) {
		$config = Configure::read('Socialites.Providers.' . $provider);
		$fqcn = Configure::read('SocialitesProviderRegistry.' . $provider);
		if ($fqcn) {
			return new $fqcn($config);
		}
		throw new CakeException('Invalid fqcn for provider: ' . $provider);
	}

/**
 * Helper to create login links for supported providers
 */
	public function login($title = null, $options = array()) {
		$options = Hash::merge(array(
			'provider' => null,
		), $options);

		$provider = $options['provider'];
		unset($options['provider']);

		$title = $title ? $title: ucfirst($provider);

		if (!Configure::read('Socialites.Providers.' . $provider)) {
			return null;
		}

		switch ($provider) {
			case 'twitter':
				$config = Configure::read('Socialites.Providers.twitter');
				return $this->Html->link($title, $config['authorizeUri'], $options);
			break;

			default:
				$Provider = $this->_getProvider($provider);
				return $this->Html->link(
					$title,
					$Provider->getAuthorizationUrl(),
					$options
				);
			break;
		}
		return null;
	}

/**
 * Helper to create links for logged in users
 */
	public function linkTo($title = null, $options = array()) {
		$options = Hash::merge(array(
			'provider' => null,
		), $options);
		$provider = $options['provider'];
		unset($options['provider']);

		$title = $title ? $title: ucfirst($provider);

		if (!Configure::read('Socialites.Providers.' . $provider)) {
			return null;
		}

		$url = array(
			'plugin' => 'socialites',
			'controller' => 'socialites_users',
			'action' => 'associate',
			'provider' => $provider
		);

		return $this->Html->link($title, $url, $options);
	}

	public function providerUid() {
		$provider = $this->Session->read('Socialites.newUser.provider');
		$field = 'Socialite.' . $provider . '_uid';
		return $this->Form->input($field, array('type' => 'hidden'));
	}

}
