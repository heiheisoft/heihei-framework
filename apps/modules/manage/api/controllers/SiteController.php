<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use common\forms\LoginForm;

/**
 * 默认控制器
 */
class SiteController extends BaseController
{    
    /**
     * 接口首页.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return "管理后台接口";
    }

    /**
     * 登录处理.
     *
     * @return mixed
     */
    public function actionLogin(){
        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post(), '') && $loginForm->login()) {
            return;
        }
        $error = $loginForm->getFirstErrors();
        return $this->fail(reset($error));
    }
}
