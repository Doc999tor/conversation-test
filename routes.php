<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

// $app->add(new \Lib\Middlewares\ExampleMiddleware());

$app->get('/', function (Request $request, Response $response, array $args) {
	return $response->withRedirect('/home');
});

$app->get('/home', function (Request $request, Response $response, array $args) {
	return $response->withRedirect('/whoami');
});
$app->group('/whoami', function () use ($app) {
	$app->get ('', function (Request $request, Response $response, array $args) {
		return $response->write(file_get_contents('public/whoami/whoami.html'));
	});
	$app->post('', function (Request $request, Response $response):Response {
		$body = $request->getParsedBody();
		$name = filter_var($body['name'], FILTER_SANITIZE_STRING);

		return $response->withRedirect('/hello/' . $name);
	});
});

$app->get('/hello/{name}', 'ListCtrl:greetings');