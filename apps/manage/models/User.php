<?php
namespace manage\models;
use Yii;
use yii\db\Query;
use yii\filters\auth\HttpBearerAuth;
use models\Manager;


class User extends \models\User
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $auth_key;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manager}}';
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 验证密码
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 根据用户名查找用户
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['or',['username' => $username],['mobile' => $username]])->one();
    }

    /**
     * 根据用户名查找用户
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameAndPassword($username, $password)
    {
        $user = static::findByUsername($username);
        if(Yii::$app->security->validatePassword($password, $user->password)){
            return $user;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if(HttpBearerAuth::className() == $type){
            $tokenInfo = static::tokenToData($token);
            $userId = $tokenInfo['user_id'];
            $user = static::findIdentity($userId);
            if($user && $tokenInfo){
                $user->tokenInfo = $tokenInfo;
            }            
            return $user;
        }
        return null;
    }

    public function getUserId(){
        return $this->id;
    }

    /**
     * 获取管理
     *
     * @return bool
     */
    public function getManager(){
        $manager = new Manager();
        Manager::populateRecord($manager, $this->toArray());
        return $manager;
    }

    /**
     * 检测请求当前控制器是否允许
     *
     * @return bool
     */
    public function checkAccess()
    {
        $userId = $user->getId();
        $identity = $user->getIdentity();
        $manager = $identity->getManager();
        if(($permissions = $manager->getPermissions()) == null){
            return false;
        }
        if($permissions->privileges == 'all'){
            if($this->disallowed_privileges){
                $disallowedPrivileges = array_filter(array_unique(explode(',', $this->disallowed_privileges)));
            }
            return true;
        }

        $allowPrivileges = $permissions->getAllowPrivileges();
        return in_array($route, $allowPrivileges);
    }
}
