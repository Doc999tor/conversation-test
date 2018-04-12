<?php
require 'vendor/autoload.php';
require 'db_config.php';

$config = [
	'displayErrorDetails' => true,
	'determineRouteBeforeAppMiddleware' => false,
	'routerCacheFile' => false,
	'db' => [
		'driver'   => ZUZ_DB_DRIVER,
		'host'   => ZUZ_DB_HOST,
		'user' => ZUZ_DB_USER,
		'pass' => ZUZ_DB_PASSWORD,
		'dbname' => ZUZ_DB_NAME,
	]
];

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['db'] = function ($c) {
	$db = $c['settings']['db'];
	try {
		$connection_string = "{$db['driver']}:host={$db['host']};dbname={$db['dbname']}";
		$pdo = new PDO($connection_string, $db['user'], $db['pass']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $pdo;
	} catch (PDOException $e) {
		throw $e;
	}
};

$container['ListCtrl'] = function () use ($container) {
	return new \Lib\Controllers\ListCtrl($container);
};

require_once 'routes.php';
$app->run();