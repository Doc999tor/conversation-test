<?php
namespace Lib\Models;

class Category extends Model implements IListApi {
	function __construct() {} # for future adding, editing and deleting

	/**
	 * Gets all categories
	 *
	 * @return     array
	 */
	public static function getAll() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); return; }
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