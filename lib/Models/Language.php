<?php
namespace Lib\Models;

class Language extends Model implements IListApi {
	function __construct() {} # for future adding, editing and deleting

	/**
	 * Gets all languages
	 *
	 * @return     array
	 */
	public static function getAll() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); return; }
		$connection = $container->db;

		$query = "
			SELECT language_id as id, name
			FROM language
			LIMIT 1000;
		";

		$res = $connection->query($query);
		$res = $res->fetchAll();
		return $res ?? [];
	}
}