<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface		 as Response;

$app->get('/', function (Request $request, Response $response, array $args) {
	return $response->withRedirect('/public/home/');
});

$app->get('/api/categories', 'ListCtrl:getCategories');
$app->get('/api/languages', 'ListCtrl:getLanguages');
$app->get('/api/actors', 'ListCtrl:getActors');

$app->get('/api/films', 'SearchCtrl:searchFilms');
$app->get('/api/films/log', 'SearchCtrl:showAuditLog');