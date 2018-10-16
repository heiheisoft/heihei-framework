<?php
namespace manage\controllers;

use Yii;
use models\AuthPermission;

/**
 * 权限管理
 */
class AuthPermissionController extends BaseController
{
    /**
     * 权限列表
     *
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 权限添加.
     *
     * @return string
     */
    public function actionAdd()
    {
        return $this->render('item',['actionId'=>'add','item'=>new AuthPermission()]);
    }

    /**
     * 权限修改.
     *
     * @return string
     */
    public function actionUpdate($id)
    {
        $permission = AuthPermission::findOne($id);
        return $this->render('item', ['actionId'=>'update', 'item'=>$permission]);
    }
}
