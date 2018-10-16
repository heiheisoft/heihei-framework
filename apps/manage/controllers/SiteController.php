<?php
namespace manage\controllers;

use Yii;

/**
 * 默认控制器
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 平台首页.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 登录注册
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->getIsGuest()) {
            return $this->goHome();
        }
        return $this->renderPartial('login');
    }

    /**
     * 账号退出
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
