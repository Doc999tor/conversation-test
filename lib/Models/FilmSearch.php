<?php
namespace Lib\Models;

use Lib\Helpers\StringUtils;

class FilmSearch extends Search implements ISearchable {
	protected $criteriaForValidation = [
		"title"		  => "string",
		"description" => "string",
		"category"	  => "string",
		"actor"		  => "string",
		"language"	  => "string",
	];

	public function search() {
		$container = self::getContainer();
		if (!isset($container->db)) { throw new \Exception("db is missing"); return; }
		$connection = $container->db;

		$query = "
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
				   film.title = :title
				OR film.description = :description
				OR film.category_id = (SELECT category_id FROM category WHERE name = :category)
				OR film.actor_id IN (SELECT actor_id FROM actors WHERE first_name = :actor1 or last_name = :actor2)
				OR film.language_id = (SELECT language_id FROM language where name = :language)
			GROUP BY film.film_id
			LIMIT :limit
			OFFSET :offset -- in production the OFFSET clause will be replaces with more efficient subquery
		";

		// var_dump($query);die();
		$stmt = $connection->prepare($query);

		$stmt->bindParam(':title', $this->criteria['title'], \PDO::PARAM_STR);
		$stmt->bindParam(':description', $this->criteria['description'], \PDO::PARAM_STR);
		$stmt->bindParam(':category', $this->criteria['category'], \PDO::PARAM_STR);
		$stmt->bindParam(':actor1', $this->criteria['actor'], \PDO::PARAM_STR);
		$stmt->bindParam(':actor2', $this->criteria['actor'], \PDO::PARAM_STR);
		$stmt->bindParam(':language', $this->criteria['language'], \PDO::PARAM_STR);
		$stmt->bindParam(':limit', $this->criteria['limit'], \PDO::PARAM_INT);
		$stmt->bindParam(':offset', $this->criteria['offset'], \PDO::PARAM_INT);

		$stmt->execute();

		$res = $stmt->fetchAll();
		var_dump($res);die();

		return $res;
	}
}