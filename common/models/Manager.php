<?php
namespace models;

use Yii;
/**
 * 管理员
 *
 * @author dejin <dejin@aliyun.com>
 */
class Manager extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '登录账户',
            'full_name' => '管理员名称',
            'password' => '账户密码',
            'mobile' => '手机号码',
            'email' => '电子邮件',
            'status' => '状态'
        ];
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'filter', 'filter'=>'intval'],
            [['username', 'mobile', 'email', 'full_name'], 'filter', 'filter' => 'trim'],
            [['username', 'password', 'full_name'], 'required'],
            [['username'], 'unique']
        ];
    }

    /**
     * 
     */
	public function setPassword($password)
    {
    	if(empty($password)){
    		return;
    	}
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function getAuthAssignmentAll(){
    	return $this->id ? AuthAssignment::find()->where(['user_id'=>$this->id])->all() : null;
    }

    public function getAuthAssignment($type){
        return $this->id ? AuthAssignment::find()->where(['user_id'=>$this->id,'type'=>$type])->one() : null;
    }

    public function getStatusText(){
        $status = $this->status;
        $statusTexts = [0=>'禁用', 1=>'启用'];
        return isset($statusTexts[$status]) ? $statusTexts[$status] : '未知';
    }

    public static function statusText($status = 0){
        $statusTexts = [0=>'禁用', 1=>'启用'];
        return isset($statusTexts[$status]) ? $statusTexts[$status] : '未知';
    }
}
