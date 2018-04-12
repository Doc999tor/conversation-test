<?php

namespace Lib\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

use \Lib\Models\Category;
use \Lib\Models\Actor;

class ListCtrl {
	protected $container;

	public function __construct(\Slim\Container $container) {
	    $this->container = $container;
	}

	public function getCategories (Request $request, Response $response):Response {
		Category::registerContainer($this->container);
		$categories = Category::getAll();

		return $response->withJson($categories);
	}
	public function getLanguages (Request $request, Response $response):Response {
		Language::registerContainer($this->container);
		$languages = Language::getAll();

		return $response->withJson($languages);
	}
	public function getActors (Request $request, Response $response):Response {
		Actor::registerContainer($this->container);
		$actors = Actor::getAll();

		return $response->withJson($actors);
	}
}