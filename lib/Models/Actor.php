<?php
namespace Lib\Models;

class Actor extends Model {
	function __construct() {
		// for future adding, editing and deleting
	}

	public static function getAll() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new Exception("db is missing"); return; }
		$connection = $container->db;

		$query = "
			SELECT actor_id as id, CONCAT(first_name, ' ', last_name) as name
			FROM actor
			LIMIT 1000;
		";

		$res = $connection->query($query);
		$res = $res->fetchAll();
		return $res ?? '{}';

	}
}