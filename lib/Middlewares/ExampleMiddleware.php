<?php
namespace Lib\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

class ExampleMiddleware {

	/**
	 * template for a middleware, before and after $next call we can run middleware code
	 *
	 * @param      Request   $request
	 * @param      Response  $response
	 * @param      callable  $next      controller handling the response between the middleware code
	 *
	 * @return     Response
	 */
	public function __invoke (Request $request, Response $response, callable $next) {
	    $response = $next($request, $response);
	    return $response;
	}
}