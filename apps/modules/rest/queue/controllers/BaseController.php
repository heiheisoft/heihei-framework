<?php 

namespace  modules\rest\queue\controllers;

use yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;

class BaseController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
    	$behaviors = parent::behaviors();
    	
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'optional' => ['*'],
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true
                ],
                [
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ],
        ];
        return [];
    }
}
?>