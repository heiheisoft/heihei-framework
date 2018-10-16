<?php
namespace manage\controllers;

use Yii;

/**
 * 计划任务
 */
class CronController extends BaseController
{
    /**
     * 计划任务列表。
     *
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }
}
