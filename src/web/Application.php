<?php
namespace sf\web;

class Application extends \sf\base\Application
{
	public function handleRequest()
	{
		$router = $_GET['r'];

		list($controllerName, $actionName) = explode('/', $router);
		$ucController =ucfirst($controllerName);
		$controllerName = 'jeny\\controllers\\' . $ucController. 'Controller';

		$controller = new $controllerName;

        $controller->controllerName = $controllerName;
        $controller->actionName = $actionName; 

		return call_user_func([$controller, 'action'. ucfirst($actionName)]);	
		
	}

}
