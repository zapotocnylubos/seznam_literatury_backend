<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

        $admin = new RouteList('Admin');
        $admin[] = new Route('admin/<presenter>/<action>', 'Homepage:default');

        $front = new RouteList('Front');
		$front[] = new Route('<presenter>/<action>', 'Homepage:default');

		$router[] = $admin;
		$router[] = $front;
		return $router;
	}
}
