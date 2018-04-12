<?php

namespace Lib\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

use \Lib\Models\Category;
use \Lib\Models\Language;
use \Lib\Models\Actor;

class ListCtrl extends Controller {

	/**
	 * Gets the categories.
	 *
	 * @param      Request   $request
	 * @param      Response  $response
	 *
	 * @return     Response  The categories json
	 */
	public function getCategories (Request $request, Response $response):Response {
		Category::registerContainer($this->container);
		$categories = Category::getAll();

		return $response->withJson($categories);
	}
	/**
	 * Gets the languages.
	 *
	 * @param      Request   $request   The request
	 * @param      Response  $response  The response
	 *
	 * @return     Response  The languages json
	 */
	public function getLanguages (Request $request, Response $response):Response {
		Language::registerContainer($this->container);
		$languages = Language::getAll();

		return $response->withJson($languages);
	}
	/**
	 * Gets the actors.
	 *
	 * @param      Request   $request   The request
	 * @param      Response  $response  The response
	 *
	 * @return     Response  The actors json
	 */
	public function getActors (Request $request, Response $response):Response {
		Actor::registerContainer($this->container);
		$actors = Actor::getAll();

		return $response->withJson($actors);
	}
}