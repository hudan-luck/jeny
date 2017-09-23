<?php
namespace jeny\controllers;

use sf\web\Controller;

class SiteController extends Controller
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
		/*$body = 'the body informaction';
		require '../views/site/view.php'; */
        
        $this->render('site/view', ['body' => 'the body informaction']);
        
	}
    
    public function actionData()
    {
        $data = ['first' => 'apple', 'second' => 'pear'];
        
        echo $this->toJson($data);
    }
}
