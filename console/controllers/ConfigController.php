<?php
namespace console\controllers;

use Yii;
use models\Manager;
use models\AuthAssignment;
use models\AuthRole;
use console\base\Controller;

/**
 * 系统配置
 */
class ConfigController extends Controller
{
    /**
     * 初始化所有内容
     */
    public function actionInit(){
        if($this->initManage()){
            $this->showMessage("初始化管理员成功");
        }

    }

    /**
     * 重置管理员密码
     */
    public function actionResetPassword($password = '111111'){
        $username = 'admin';
        $manager = Manager::find()->where(['username'=>$username])->one();
        if(!$manager){
            $this->showMessage("管理员不存在！");
            return false;
        }
        $this->showMessage("重置密码为:{$password}");
        $manager->password = Yii::$app->security->generatePasswordHash($password);
        return $manager->save();
    }

    /**
     * 初始化管理员
     */
    private function initManage(){

        //角色
        $authRole = AuthRole::findOne(1);
        if(!$authRole){
            $authRole = new AuthRole();
            $authRole->id = 1;
            $authRole->name = '超级管理员';
            $authRole->data = 'all';
            $authRole->save();
        }        
        else{
           $this->showMessage("角色已经存在！"); 
        }
        //管理员
        $manager = Manager::findOne(1);
        if(!$manager){
            $manager = new Manager();
            $manager->id = 1;
            $manager->username = 'admin';
            $manager->full_name = '超级管理员';
            $manager->password = Yii::$app->security->generatePasswordHash('111111');
        }
        else{
           $this->showMessage("管理员已经存在！"); 
        }

        //授权
        $authAssignment = AuthAssignment::find()->where(['user_id'=>1, 'type'=>'role'])->one();
        if(!$authAssignment){
            $authAssignment = new AuthAssignment();
            $authAssignment->user_id = 1;
            $authAssignment->type = 'role';
            $authAssignment->data = '1';
            $authAssignment->save();
        }        
        else{
           $this->showMessage("管理员已经授权！"); 
        }
    }
}
