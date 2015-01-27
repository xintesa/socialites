<?php

App::uses('AppHelper', 'View/Helper');

class SocialitesHelper extends AppHelper {

	public $helpers = array(
		'Html',
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
	public function login($title, $options = array()) {
		$options = Hash::merge(array(
			'provider' => null,
		), $options);

		$provider = $options['provider'];
		unset($options['provider']);

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

}
