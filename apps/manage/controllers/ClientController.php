<?php
namespace manage\controllers;

use Yii;

/**
 * 客户端管理
 */
class ClientController extends BaseController
{
    /**
     * 客户端列表.
     *
     * @return view
     */
    public function actionList()
    {
        return $this->render('list');
    }
}
