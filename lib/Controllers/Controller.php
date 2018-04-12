<?php
namespace Lib\Controllers;

abstract class Controller {
	protected $container;

	public function __construct(\Slim\Container $container) {
	    $this->container = $container;
	}
}