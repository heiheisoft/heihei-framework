<?php
namespace models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * 消息队列表
 *
 * @author dejin <ldj@hianto2o.com>
 */
class QueueData extends ActiveRecord
{
    public static $db = null;

    public static function getDb()
    {
        return self::$db ?:Yii::$app->getDb();
    }

	public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * 生成序号.
     * @param string|null $key 扩展值.
     * @return string 序号串.
     */
    public function generateSerialNumber($key = false){
        $curMicroTime = microtime();        
        list($micro,$curTime) = explode(' ', $curMicroTime);
        $yearFirstSecondTime = strtotime(date("Y-01-01", $curTime));
        if(is_numeric($key) && $key > 0){
            $micro = ($key % 300000) / 300000; 
        }
        $secondTime = $curTime - $yearFirstSecondTime + $micro;        
        $year = date("Y", $curTime);
        //time_nanosleep(0, 3000);
        $secondTime = ($secondTime * 300000) . '.';
        $key = strchr($secondTime,'.',true);
        $key = str_pad($key, 13,'0', STR_PAD_RIGHT);
        return $year . $key;
    }
}
