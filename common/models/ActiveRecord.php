<?php
namespace models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * ActiveRecord行为基础类
 *
 * @author dejin <dejin@aliyun.com>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function init(){
        parent::__clone();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $createdAtAttribute = $this->hasAttribute('created_at') ? 'created_at' : false;
        $updatedAtAttribute = $this->hasAttribute('updated_at') ? 'updated_at' : false;
        if( $createdAtAttribute == false && $updatedAtAttribute == false){
            return [];
        }

        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => $createdAtAttribute,
                'updatedAtAttribute' => $updatedAtAttribute
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            $columns = static::getTableSchema()->columns;
            foreach ($values as $name => $value) {
                if (isset($columns[$name])) {
                    $this->$name = isset($columns[$name]) ? $columns[$name]->phpTypecast($value) : $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    /**
     * 获取第一个错误信息.
     * @param string|null $attribute .
     * @return bool whether there is any error.
     */
    public function getErrorMessage($attribute = null)
    {
        $errors = $this->getErrors();
        if (empty($errors)) {
            return '';
        }
        if($attribute != null){
            return isset($errors[$attribute]) ? "[{$attribute}]" . reset($errors[$attribute]) : '';
        }
        $keys = array_keys($errors);
        return '[' . $keys[0] . ']' . reset($errors[$keys[0]]);
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