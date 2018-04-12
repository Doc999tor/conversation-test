<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

// $app->add(new \Lib\Middlewares\ExampleMiddleware());

$app->get('/', function (Request $request, Response $response, array $args) {
	return $response->withRedirect('/home');
});

$app->get('/api/categories', 'ListCtrl:getCategories');
$app->get('/api/languages', 'ListCtrl:getLanguages');
$app->get('/api/actors', 'ListCtrl:getActors');