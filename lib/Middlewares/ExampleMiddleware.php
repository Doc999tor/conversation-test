<?php
namespace Lib\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

class ExampleMiddleware {
	public function __invoke (Request $request, Response $response, callable $next) {
	    $response = $next($request, $response);
	    return $response;
	}
}