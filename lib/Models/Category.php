<?php
namespace Lib\Models;

class Category extends Model {
	function __construct() {
		// for future adding, editing and deleting
	}

	public static function getAll() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new Exception("db is missing"); return; }
		$connection = $container->db;

		$query = "
			SELECT category_id as id, name
			FROM category
			LIMIT 1000;
		";

		$res = $connection->query($query);
		$res = $res->fetchAll();
		return $res ?? [];
	}
}