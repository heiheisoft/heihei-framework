<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\db\Expression;
use models\AuthRole;
use models\AuthAssignment;


/**
 * 角色
 */
class AuthRoleController extends BaseController
{	
    /**
     * 角色列表
     *
     * @parent auth-role/list
     * @return array
     */
    public function actionList(){
        $query = AuthRole::find()->asArray();
        return $query->all();
    }

    /**
     * 添加角色
     *
     * @parent auth-role/add
     * @return array
     */
    public function actionAdd(){
        $role = new AuthRole();
        return $this->save($role);
    }

    /**
     * 修改角色
     *
     * @parent auth-role/update
     * @return array
     */
    public function actionUpdate($id){
        $role = AuthRole::findOne($id);
        if(!$role){
            return $this->fail("ID:{$id} 找不到！");
        }
        return $this->save($role);
    }

    /**
     * 删除角色
     *
     * @return array
     */
    public function actionDelete($id){
        $isExist = AuthAssignment::find()->asArray()->select('user_id')->where(new Expression("CONCAT(',',`data`,',') LIKE '%,{$id},%'"))->andWhere(['type'=>'role'])->one();
        if($isExist){
           return $this->fail('这个角色还在使用不能删除！'); 
        }
        $role = AuthRole::findOne($id);
        if($role){
            $role->delete();
        }
        return;
    }

    protected function save($role){
        $request = Yii::$app->getRequest();
        $post = $request->post();
        $role->name = $request->post('name');
        $roleData = $request->post('data', []);
        $role->data = in_array('all', $roleData) ? 'all' : implode(',', $roleData);
        if(!$role->save()){
            return $this->fail($role->getErrorMessage());
        }        
        return;
    }
}
