<?php
namespace manage\controllers;

use Yii;
use models\AuthRole;

/**
 * 角色管理
 */
class AuthRoleController extends BaseController
{
    /**
     * 角色列表.
     *
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 角色添加.
     *
     * @return string
     */
    public function actionAdd()
    {
        $this->getView()->title = "添加角色";
        return $this->render('item',['actionId'=>'add','item'=>new AuthRole()]);
    }

    /**
     * 角色修改.
     *
     * @return string
     */
    public function actionUpdate($id)
    {
        $role = AuthRole::findOne($id);
        $this->getView()->title = "修改角色";
        return $this->render('item', ['actionId'=>'update', 'item'=>$role]);
    }
}
