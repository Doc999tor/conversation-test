<?php
namespace Lib\Models;

use Lib\Helpers\StringUtils;

class FilmSearch extends Search implements ISearchable {
	/**
	 * search criteria validation rules. for now simple type check, can be and will be extended
	 * @var        array
	 */
	protected $criteriaForValidation = [
		"title"		  => "string",
		"description" => "string",
		"category"	  => "string",
		"actor"		  => "string",
		"language"	  => "string",
	];

	/**
	 * Searches films by all search criteria, updates the search_log table in transaction, rollbacks both if not succeeded
	 * Pagination supported, defaults to limit 1000 and offset 0
	 *
	 * @throws     \Exception  Error Processing Request
	 *
	 * @return     array       found films, can be array of \Lib\Models\Film instances
	 */
	public function search():array {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); }
		$connection = $container->db;

		$search_query = "
			SELECT
				 film.film_id AS id
				,film.title AS title
				,film.description AS description
				,category.name AS category
				,film.release_year
				,lang.name AS language
				,film.length AS length
				,film.rating AS rating
				,GROUP_CONCAT(
					CONCAT(
						toCapitalCase(actor.first_name), ' ', toCapitalCase(actor.last_name)
					)
					SEPARATOR ', '
				) AS actors
			FROM category
			LEFT JOIN film_category ON category.category_id = film_category.category_id
			LEFT JOIN film ON film_category.film_id = film.film_id
			LEFT JOIN language lang ON lang.language_id = film.language_id
			JOIN film_actor ON film.film_id = film_actor.film_id
			JOIN actor ON film_actor.actor_id = actor.actor_id
			WHERE
				   film.title LIKE :title
				OR film.description LIKE :description
				OR film_category.category_id = (SELECT category_id FROM category WHERE name = :category)
				OR film_actor.actor_id IN (SELECT actor_id FROM actor WHERE first_name = :actor1 or last_name = :actor2)
				OR film.language_id = (SELECT language_id FROM language WHERE name = :language)
			GROUP BY film.film_id
			LIMIT :limit
			OFFSET :offset -- in production the OFFSET clause will be replaces with more efficient subquery
		";

		$connection->beginTransaction();
		try {
			$stmt = $connection->prepare($search_query);

			$stmt->bindValue(
				 ':title'
				,isset($this->criteria['title'])
					? "%{$this->criteria['title']}%"
					: ''
				,\PDO::PARAM_STR
			);
			$stmt->bindValue(
				 ':description'
				,isset($this->criteria['description'])
					? "%{$this->criteria['description']}%"
					: ''
				,\PDO::PARAM_STR
			);

			$stmt->bindValue(':category', $this->criteria['category'] ?? '', \PDO::PARAM_STR);
			$stmt->bindValue(':actor1', $this->criteria['actor'] ?? '', \PDO::PARAM_STR);
			$stmt->bindValue(':actor2', $this->criteria['actor'] ?? '', \PDO::PARAM_STR);
			$stmt->bindValue(':language', $this->criteria['language'] ?? '', \PDO::PARAM_STR);

			$stmt->bindParam(':limit', $this->limit, \PDO::PARAM_INT);
			$stmt->bindParam(':offset', $this->offset, \PDO::PARAM_INT);

			$stmt->execute();
			$res = $stmt->fetchAll(); # it can be an array of \Lib\Models\Film instances

			### update the log table ###
			$search_row_count = $stmt->rowCount();
			$log_query = "
				INSERT INTO search_log(search_types, search_values, result_count)
				VALUES (:search_types, :search_values, {$search_row_count})
				-- search_timestamp: server time!!! no client timezone!!!
			";
			$stmt = $connection->prepare($log_query);

			$stmt->bindValue(':search_types', implode(', ', array_keys($this->criteria)) ,\PDO::PARAM_STR);
			$stmt->bindValue(':search_values', implode(', ', array_values($this->criteria)) ,\PDO::PARAM_STR);

			$stmt->execute();

			$connection->commit(); # happy end
		} catch (\PDOException $e) {
			$connection->rollback();
			throw new \Exception("Error Processing Request", 1);
		}

		return $res ?? [];
	}

	/**
	 * Gets the search log with defined pagination
	 *
	 * @return     array       The search log
	 */
	public function getSearchLog():array {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); }
		$connection = $container->db;

		$log_query = "
			SELECT search_types, search_values, result_count, search_timestamp -- server time!!! no client timezone!!!
			FROM search_log
			LIMIT :limit
			OFFSET :offset -- in production the OFFSET clause will be replaces with more efficient subquery
		";
		$stmt = $connection->prepare($log_query);

		$stmt->bindParam(':limit', $this->limit, \PDO::PARAM_INT);
		$stmt->bindParam(':offset', $this->offset, \PDO::PARAM_INT);

		$stmt->execute();

		$res = $stmt->fetchAll();
		return $res ?? [];
	}
}