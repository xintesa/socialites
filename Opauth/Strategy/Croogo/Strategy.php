<?php

/**
 * Croogo strategy for Opauth
 *
 * Based from Facebook Strategy class
 *
 * @copyright    Copyright © 2012 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @package      Opauth.FacebookStrategy
 * @license      MIT License
 */
namespace Opauth\Strategy\Croogo;

use Opauth\AbstractStrategy;

class Strategy extends AbstractStrategy {

	protected $_scheme = 'http';

	protected $_server = 'croogo.dev';

/**
 * Compulsory config keys, listed as unassociative arrays
 * eg. array('app_id', 'app_secret');
 */
	public $expects = array('client_id', 'secret');

/**
 * Map response from raw data
 *
 * @var array
 */
	public $responseMap = array(
		'name' => 'user.name',
		'uid' => 'user.id',
		'info.name' => 'user.name',
		'info.nickname' => 'user.username',
		'info.description' => 'user.bio',
		'info.image' => 'user.image',
		'info.urls.website' => 'user.website'
	);

/**
 * Generate endpoint URL
 */
	protected function _endPoint($uri) {
		return $this->_scheme . '://' . $this->_server . $uri;
	}

/**
 * Auth request
 */
	public function request() {
		$url = $this->_endPoint('/oauth/authorize');
		$strategyKeys = array(
			'scope',
			'state',
			'response_type',
			'display',
			'auth_type',
			'client_id',
		);
		$params = $this->addParams($strategyKeys);
		$params['response_type'] = 'code';
		$params['redirect_uri'] = $this->callbackUrl();
		$this->http->redirect($url, $params);
	}

/**
 * Internal callback
 */
	public function callback() {
		if (!array_key_exists('code', $_GET) || empty($_GET['code'])) {
			return $this->codeError();
		}

		$url = $this->_endPoint('/oauth/token');
		$params = $this->callbackParams();
		$response = $this->http->get($url, $params);
		$results = json_decode($response, true);

		if (empty($results['access_token'])) {
			return $this->tokenError($response);
		}

		$me = $this->me($results['access_token']);
		if (!$me) {
			$error = array(
				'code' => 'me_error',
				'message' => 'Failed when attempting to query for user information'
			);
			return $this->response(null, $error);
		}

		$response = $this->response(null);
		$response->credentials = array(
			'token' => $results['access_token'],
			'expires' => date('c', time() + $results['expires_in'])
		);
		$response->raw = $me;
		$response->uid = $me['user']['id'];
		$response->name = $me['user']['name'];
		return $response;
	}

/**
 * Helper method for callback()
 *
 * @return array Parameter array
 */
	protected function callbackParams() {
		$params = array(
			'redirect_uri'=> $this->callbackUrl(),
			'code' => trim($_GET['code']),
			'grant_type' => 'authorization_code',
		);
		$strategyKeys = array(
			'client_id',
			'secret' => 'client_secret',
		);
		$params = $this->addParams($strategyKeys, $params);
		return $params;
	}

/**
 * Create a codeError message
 *
 * @return array of error code and message
 */
	protected function codeError() {
		$error = array(
			'code' => $_GET['error'],
			'message' => $_GET['error_description'],
		);

		return $this->response($_GET, $error);
	}

/**
 * Create a tokenError message
 *
 * @return array of error code and message
 */
	protected function tokenError($raw) {
		$error = array(
			'code' => 'access_token_error',
			'message' => 'Failed when attempting to obtain access token',
		);
		return $this->response($raw, $error);
	}

/**
 * Queries server for user info
 *
 * @param string $access_token
 * @return array Parsed JSON results
 */
	protected function me($accessToken) {
		$url = $this->_endPoint('/api/v1.0/users/me.json');
		$me = $this->http->get($url, array('access_token' => $accessToken));
		if (empty($me)) {
			return false;
		}
		return $this->recursiveGetObjectVars(json_decode($me));
	}

}
