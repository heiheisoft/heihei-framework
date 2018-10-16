<?php
namespace manage\controllers;

use Yii;

/**
 * 消息队列
 */
class QueueController extends BaseController
{
    /**
     * 消息队列列表.
     *
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }

    /**
     * 消息队列出错列表.
     *
     * @return string
     */
    public function actionErrorsList()
    {
        return $this->render('errors-list');
    }
}
