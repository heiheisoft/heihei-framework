<?php
namespace modules\rest\queue\controllers;

use common\forms\LoginForm;
use heihei\rest\Controller AS BaseController;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;


/**
 * 默认控制器
 */
class QueueController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'push' => ['post'],
            ],
        ];
        return $behaviors;
    }

    /**
     * 接口首页.
     *
     * @return mixed
     */
    public function actionIndex()
    {
    	
        return "消息队列接口";
    }

    /**
     * 加入.
     *
     * @return mixed
     */
    public function actionPush()
    {
        $request = Yii::$app->getRequest();
        $queue = Yii::$app->get('queue');
        $key = $request->post('key');
        $value = $request->post('value');
        $decodeValue = json_decode($value, true);
        if($decodeValue){
            $value = $decodeValue;
        }
        $queue->push($key, $value);
        return "加入到队列";
    }
}
