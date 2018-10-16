<?php
namespace console\jobs\queue;

use Yii;
use yii\log\Logger;
use yii\base\Exception;

/**
 *  消息队列基础类
 */
class BaseJob extends \console\jobs\BaseJob {

    /**
     * @var 队列编号
     */
    public $queueId;

    /**
    * 延迟执行
    *
    * @param $seconds int 延迟秒数
    **/
    protected function delayExecute($seconds){
        $queue = Yii::$app->queue;
        try{
            $queue->delayExecute($this->queueId, $seconds);
        }
        catch(Exception $ex){
            $this->logger->log($ex->getMessage(), Logger::LEVEL_WARNING, 'queue');
        }
        
        return true;
    }
}