<?php
namespace console\jobs;

use Yii;
use yii\base\Component;

/**
 * 工作基础类
 */
class BaseJob extends Component
{
	public $logger;

	public $controller;

	public $app;

    public function showMessage($message){
    	$this->controller->showMessage($message);
    }
}
