<?php
namespace Lib\Controllers;

abstract class Controller {
	/**
	 * dependency injection container
	 * @var \Slim\Container
	 */
	protected $container;

	/**
	 * Slim instantiate a controller and passes in the DI container
	 *
	 * @param      \Slim\Container  $container
	 */
	public function __construct(\Slim\Container $container) {
	    $this->container = $container;
	}
}