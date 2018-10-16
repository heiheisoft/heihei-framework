<?php
namespace rest\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;

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
        $access = [
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
        ];
        $access = [
            'class' => AccessControl::className(),
            'user' => false
        ];
        return [
            'access' => $access,
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }
}
