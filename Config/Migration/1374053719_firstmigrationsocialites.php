<?php

class FirstMigrationSocialites extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'socialites' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
					'twitter_uid' => array('type' => 'integer', 'null' => true, 'default' => null, 'after' => 'user_id'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'user_id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci'),
				),

			),
		),

		'down' => array(
			'drop_table' => array(
				'access_tokens', 'auth_codes', 'clients', 'refresh_tokens'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

}
