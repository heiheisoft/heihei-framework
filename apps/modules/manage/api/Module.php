<?php 
namespace  modules\manage\api;
use Yii;

class Module extends \yii\base\Module{
	public function init(){
        parent::init();
        $components = $this->getComponents();
        if(isset($components['errorHandler'])){
            Yii::$app->getErrorHandler()->unregister();
            Yii::$app->set('errorHandler', $components['errorHandler']);
            Yii::$app->getErrorHandler()->register();
        }
        unset($components['errorHandler']);
        foreach ($components as $name => $config) {
        	Yii::$app->set($name, $config);
        }
        Yii::$app->user->loginUrl = null;
    }
}


 ?>