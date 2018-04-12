<?php
namespace Lib\Models;

abstract class Model {
	/**
	 * dependency injection container
	 * @var \Slim\Container
	 */
	protected static $container;

	/**
	 * Before getting access to the DI, we register the container
	 * The conainer is added once for Model
	 *
	 * @param      \Slim\Container  $container
	 */
	public static function registerContainer(\Slim\Container $container) {
		if (is_null(self::$container)) {
			self::$container = $container;
		}
	}

	/**
	 * returns the DI container
	 *
	 * @return     \Slim\Container
	 */
	protected static function getContainer ():\Slim\Container {
		return self::$container;
	}
}