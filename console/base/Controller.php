<?php
namespace console\base;

use Yii;
use yii\console\Controller as BaseController;


/**
 * 基础控制器
 */
class Controller extends BaseController
{
    public $logger;

    /**
     * @inheritdoc
     */
    public function init(){
    	$this->logger = Yii::$app->log->getLogger();
    }

    public function showMessage($message = '', $addDate = true){
        if($addDate && !empty($message)){
            $message =  '[' . Date('Y-m-d H:i:s', time()) . ']' . $message;
        }
    	$this->stdout($message . "\r\n");
    }
}