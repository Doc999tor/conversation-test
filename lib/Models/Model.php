<?php
namespace Lib\Models;

/**
*
*/
abstract class Model {
	/**
	* @var \Slim\Container
	*/
	protected static $container;

	public static function registerContainer(\Slim\Container $container) {
		if (is_null(self::$container)) {
			self::$container = $container;
		}
	}

	protected static function getContainer ():\Slim\Container {
		return self::$container;
	}
}