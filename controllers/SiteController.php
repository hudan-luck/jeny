<?php
namespace jeny\controllers;

class SiteController
{
    /**
	 * 
	 */
	public function actionTest()
	{
		echo 'hello world';
	}
    	
	public function actionView() 
	{
		$body = 'the body informaction';
		require '../views/site/view.php';
	}
}
