<?php
namespace jeny\controllers;

use sf\web\Controller;
use jeny\models\User;
use sf\db\ConnectionSf;

class SiteController extends Controller
{
    /**
	 * 简单测试 
	 */
	public function actionTest()
	{
		echo 'hello world';
	}

    /**
     *  
     */	
	public function actionView() 
	{
		/*$body = 'the body informaction';
		require '../views/site/view.php'; */
        
        $this->render('site/view', ['body' => 'the body informaction']);
        
	}

    /*
     * json 数据转化
     *
     */
    public function actionData()
    {
        $data = ['first' => 'apple', 'second' => 'pear'];
        
        echo $this->toJson($data);
    }

    /*  
     * 查找单条数据
     */
    public function actionDbOne()
    {
        $data = User::findOne(['id' => 1, 'name' => 'wxdr']);
        echo $this->toJson($data);           
    }
    
    /*  
     * 更新单条数据
     */
    public function actionDbUp(){
        $where = ['id' => 1];
        $set   = ['age' => 20];
        $result = User::updateAll($where, $set);
        echo $result;die;
    }
    
    /*  
     * 删除单条数据
     */
    public function actionDbDel()
    {   
        $where = ['id' => 2];
        $result = User::deleteAll($where);
        var_dump($result);die;
    }
    
    public function actionDbIns()
    {
        $user_model  = new User;
        $user_model->name = 'test';
        $user_model->age = 10;
        $result = $user_model->insert();
        var_dump($result);die;
    }
    
    public function actionCache()
    {
        $cache = ConnectionSf::createObject('cache');
        $result = $cache->set('test', 'test');
        $result = $cache->get('test');
        $cache->flush();
        $result = $cache->get('test');
        var_dump($result);die;
    }
    
    public function actionRedis()
    {
        $redis = ConnectionSf::createObject('redis');
        $result = $redis->set('china', 'hello world');
        var_dump($redis->get('china'));die;
    }
}
