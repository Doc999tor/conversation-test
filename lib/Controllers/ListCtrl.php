<?php

namespace Lib\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

class ListCtrl {
	protected $container;

	public function __construct(\Slim\Container $container) {
	    $this->container = $container;
	}

	public function greetings (Request $request, Response $response, array $args):Response {
		// $params = $request->getQueryParams();
		$body = $request->getParsedBody();

		$name = filter_var($args['name'], FILTER_SANITIZE_STRING);
		$date = $this->container->db
			->query("SELECT curdate() as date")
			->fetch()['date'];

		$body = $response->getBody();
		$body->write($date);
		return $response;
	}
}