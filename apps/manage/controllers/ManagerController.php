<?php
namespace manage\controllers;

use Yii;
use models\Manager;

/**
 * 管理管理
 */
class ManagerController extends BaseController
{
    /**
     * 管理员列表.
     *
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 管理员添加.
     *
     * @return string
     */
    public function actionAdd()
    {
        return $this->render('item',['actionId'=>'add','item'=>new Manager()]);
    }

    /**
     * 管理员修改.
     *
     * @return string
     */
    public function actionUpdate($id)
    {
        $manager = Manager::findOne($id);
        return $this->render('item', ['actionId'=>'update', 'item'=>$manager]);
    }
}
