<?php
namespace rest\controllers;

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
     * 显示首页.
     *
     * @return string
     */
    public function actionIndex()
    {
        return "REST API接口";
    }

    /**
     * 登录注册
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return '登录';
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
