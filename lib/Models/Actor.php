<?php
namespace Lib\Models;

class Actor extends Model implements IListApi {
	function __construct() {} # for future adding, editing and deleting

	public static function getAll() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); return; }
		$connection = $container->db;

		$query = "
			SELECT
			  actor_id as id
			, CONCAT(
				CONCAT(UCASE(LEFT(first_name, 1)), SUBSTRING(first_name, 2)),
				' ',
				CONCAT(UCASE(LEFT(last_name, 1)), SUBSTRING(last_name, 2))
			) as name
			FROM actor
			LIMIT 1000;
		";

		$res = $connection->query($query);
		$res = $res->fetchAll();
		return $res ?? [];
	}
}