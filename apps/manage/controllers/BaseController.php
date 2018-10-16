<?php
namespace manage\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * 基础控制器
 */
class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ($this->checkAccess()) {
            return true;
        }

        throw new ForbiddenHttpException('不允许请求');
    }

    /**
     * 检测请求当前控制器是否允许
     *
     * @return bool
     */
    public function checkAccess()
    {   
        $user = Yii::$app->getUser();
        if($user->getIsGuest()){
            return true;
        }
        return $user->can($this->route);
    }
}
