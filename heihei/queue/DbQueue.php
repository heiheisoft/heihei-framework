<?php
namespace heihei\queue;

use Yii;

class DbQueue extends Queue
{
    /**
     * @var 队列类使用的数据库连接。
     */
    private $_db = null;

    /**
     * @var 字符串ActiveRecord类的名称。
     */
    public $modelClass = 'models\QueueData';

    /**
     * @var 批量出列个数。
     */
    public $batchPopLimit = 10;

    /**
	 * 设置这个队列类使用的数据库连接。
	 * 默认情况下，“db”应用程序组件用作数据库连接。
	 */
    public function setDb($value){
    	if(is_string($value)){
    		$this->_db = Yii::$app->$value;
    	}
    	else if(is_array($value)){
    		$this->_db = Yii::createObject($value);
    	}
    	else{
    		$this->_db = $value;
    	}    	
    }

    /**
	 * 返回这个队列类使用的数据库连接。
	 * 默认情况下，“db”应用程序组件用作数据库连接。
	 *
	 * @return 连接这个AR类使用的数据库连接。
	 */
    public function getDb(){
    	if($this->_db == null){
    		$this->_db = Yii::$app->getDb();
    	}
    	return $this->_db;
    }

    /**
     * 入列
     * @param string $key 队列键名
     * @param array $value 队列值
     * @param array $topic 主题
     * @param array $delaySeconds 延迟执行时间
     */
    public function push($key, $value, $topic='', $delaySeconds = 0){
    	$modelClass = $this->modelClass;
    	$queueData = new $modelClass;
		$queueData->queue_id = $queueData->generateSerialNumber();
		$queueData->topic = $topic ?: 'default';
		$queueData->key = $key;
        if(empty($value)){
            $queueData->value = '';
        }
        else{
            $queueData->value = is_object($value) || is_array($value) ?  json_encode($value) : $value;
        }
		$queueData->status = 0;
		$queueData->run_time = $delaySeconds ? time() + $delaySeconds : 0;
		$queueData->result_desc = '';
		return $queueData->save();

    }

    /**
    * 延迟执行
    *
    * @param $queueId int 源队列ID
    * @param $seconds int 延迟秒数
    **/
    public function delayExecute($queueId, $seconds){
        $queue = QueueData::find()->select(['key','value','topic'])->where(['queue_id'=>$queueId])->one($this->_db);
        if(!$queue){
            throw new UserException("队列ID:{$queueId}信息找不到！");
        }
        $value = json_decode($queue['value'], true);
        $this->push($queue['key'], $value, $queue['topic'], $seconds);
    }

    /**
     * 取出队列
     * @param string $key
     */
    public function pop($topic = ''){
    	$queues = $this->batchPop($topic, 1);
    	return $queues && count($queues) > 0 ? $queues[0] : false;
    }

    /**
	* 待执行列表
	*
	* @param string $topic 频道
	* @param int $limit 队列ID
	**/
	public function batchPop($topic = ''){
		$topic = $topic?:'default';
		$modelClass = $this->modelClass;
		$queueDataQuery = $modelClass::find()->orderBy('queue_id ASC, created_at ASC')->asArray()->limit($this->batchPopLimit);
		$queueDataQuery->select(['queue_id', 'topic', 'key', 'value', 'status', 'run_time']);
		$queueDataQuery->where(['topic'=>$topic,'status'=>0])->andWhere(['<=','run_time', time()]);
		return $queueDataQuery->all($this->_db);
	}



    

    /**
     * 设置队列执行成功
     * @param string $queueId 队列ID
     */
    public function pushQueueSuccess($queueId){
    	$modelClass = $this->modelClass;
		$queueData = $modelClass::findOne($queueId);
		$queueData->status = 1;
		$queueData->result_desc = "执行成功！";
		return $queueData->save();
	}

	/**
     * 设置队列执行失败
     * @param string $queueId 队列ID
     * @param string $message 失败的消息内容
     */
	public function pushQueueFail($queueId, $message){
		$modelClass = $this->modelClass;
		$queueData = $modelClass::findOne($queueId);
		$queueData->status = 2;
		$queueData->result_desc = $message;
		return $queueData->save();
	}
}
