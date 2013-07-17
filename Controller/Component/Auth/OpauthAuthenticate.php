<?php

class OpauthAuthenticate extends BaseAuthenticate {

	public function authenticate(CakeRequest $request, CakeResponse $response) {
		App::import('Vendor', array('file' => 'autoload'));
		$config = Configure::read('Opauth');
		try {
			$Opauth = new Opauth\Opauth($config);
			$result = $Opauth->run();
			return $result->info;
		} catch (Exception $e) {}
		return false;
	}

}
