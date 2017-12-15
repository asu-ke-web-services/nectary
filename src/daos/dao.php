<?php

namespace Nectary;

use PDO;

/**
 * Just a common class for all daos to extend
 *
 * @package Dao
 */
abstract class Dao {
	public $db;

	/**
	 * Set up the PDO
	 *
	 * @constructor
	 * @param PDO $db
	 */
	public function __construct( PDO $db ) {
		$this->set_pdo( $db );
	}

	/**
	 * Clean up
	 *
	 * @deconstructor
	 */
	public function __destruct() {
		unset( $this->db );
	}

	public function set_pdo( PDO $db ) {
		$this->db = $db;
	}

	/**
	 * Child classes need to implement get_by_criterion
	 *
	 * @abstract
	 * @param  array|null $options
	 * @return mixed
	 */
	abstract public function get_by_criterion( $options );
}
