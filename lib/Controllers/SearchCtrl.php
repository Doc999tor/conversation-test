<?php

namespace Lib\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

use \Lib\Models\FilmSearch;

class SearchCtrl extends Controller {
	public function searchFilms (Request $request, Response $response) {
		$params = $request->getQueryParams();

		if (isset($params['limit'])) {
			$limit = filter_var($params['limit'], FILTER_SANITIZE_NUMBER_INT);
			unset($params['limit']);
		}
		if (isset($params['offset'])) {
			$offset = filter_var($params['offset'], FILTER_SANITIZE_NUMBER_INT);
			unset($params['offset']);
		}
		$filtered_params = array_map(function ($key) use ($params) {
			return [filter_var($key, FILTER_SANITIZE_STRING) => filter_var($params[$key], FILTER_SANITIZE_STRING)];
		}, array_keys($params));

		FilmSearch::registerContainer($this->container);
		$search = new FilmSearch();
		foreach ($filtered_params as $param) {
			$search->addSearchCriterion(key($param), $param[key($param)]);
		}

		if (isset($limit)) {
			$search->setLimit((int)$limit);
		}
		if (isset($offset)) {
			$search->setOffset((int)$offset);
		}

		$films = $search->search();

		return $response->getBody()->write('');
	}
}
