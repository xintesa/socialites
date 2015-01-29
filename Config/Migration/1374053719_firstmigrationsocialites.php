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
					'twitter_uid' => array('type' => 'string', 'limit' => 50, 'null' => true, 'default' => null, 'after' => 'user_id'),
					'facebook_uid' => array('type' => 'string', 'limit' => 50, 'null' => true, 'default' => null, 'after' => 'twitter_uid'),
					'google_uid' => array('type' => 'string', 'limit' => 50, 'null' => true, 'default' => null, 'after' => 'facebook_uid'),
					'github_uid' => array('type' => 'string', 'limit' => 50, 'null' => true, 'default' => null, 'after' => 'google_uid'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'user_id', 'unique' => 1),
						'twitter_uids' => array('column' => 'twitter_uid', 'unique' => true),
						'facebook_uids' => array('column' => 'facebook_uid', 'unique' => true),
						'google_uids' => array('column' => 'google_uid', 'unique' => true),
						'github_uids' => array('column' => 'github_uid', 'unique' => true),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci'),
				),

			),
		),

		'down' => array(
			'drop_table' => array(
				'socialites',
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
