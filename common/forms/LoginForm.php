<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use yii\base\ModelEvent;

/**
 * 登陆表单
 */
class LoginForm extends Model
{
    public $username;
    public $mobile;
    public $smscode;
    public $password;

    public $autoSignup = false;

    private $_user;

    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    /**
     * @inheritdoc
     */
    public function init(){
        $this->setScenario('username');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'mobile'=>'手机号码',
            'smscode'=>'验证码',
            'password'=>'密码'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2],

            ['mobile', 'trim'],
            ['mobile', 'required'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],

            ['smscode', 'required'],
            ['smscode', 'validateSmsCode'],
            ['smscode', 'string', 'min' => 4, 'max' => 6],

            ['password', 'required']
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['username'] = ['username', 'password'];
        $scenarios['password'] = ['mobile', 'password'];
        $scenarios['smscode'] = ['mobile', 'smscode'];
        return $scenarios;
    }

    /**
     * 
     */
    public function beforeLogin()
    {
        $event = new ModelEvent;
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        return $event->isValid;
    }

    /**
     * 
     */
    public function afterLogin()
    {
        $this->trigger(self::EVENT_AFTER_LOGIN);
    }

    /**
     * 短信验证
     */
    public function validateSmsCode($attribute, $params)
    {
        //TODO 短信验证
    }

    /**
     * 会员登陆.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function login()
    {
        if (!$this->validate()) {
            return null;
        }
        if (!$this->beforeLogin()) {
            return false;
        }
        $user = $this->getUser();
        if($user){
            if($this->scenario != 'smscode' && !$user->validatePassword($this->password)){
                $this->addError('password', '密码不对！');
                return null;
            }
        }
        elseif($this->autoSignup && $scenario == 'smscode'){
            $userClassName = Yii::$app->getUser()->identityClass;
            $user = new $userClassName();
            $user->username = $this->mobile;
            $user->mobile = $this->mobile;
            $user->setPassword(uniqid());
            $user->created_at = time();
            if($user->save() == false){
                $this->addError('mobile', '用户登录失败！');return null;
            }
        }
        else{
            $this->addError($this->scenario == 'username' ? 'username' : 'mobile', '用户信息找不到！');return null;
        }
        return $this->vaildLogin($user);
    }

    public function getUser(){
        $userClassName = Yii::$app->getUser()->identityClass;
        if(!$this->_user){
            $this->_user = $userClassName::findByUsername($this->scenario == 'username' ? $this->username : $this->mobile);
        }        
        return $this->_user;
    }

    /**
     * 商家会员登陆通过ID.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function loginIdentity($id)
    {
        $userClassName = Yii::$app->getUser()->identityClass;
        $this->_user = $userClassName::findIdentity($id);
        if (!$this->beforeLogin()) {
            return false;
        }
        if(!$this->_user)return null;
        return $this->vaildLogin($this->_user);
    }

    private function vaildLogin($user){
        Yii::$app->user->login($user);
        $this->afterLogin();
        return $user;
    }
}
