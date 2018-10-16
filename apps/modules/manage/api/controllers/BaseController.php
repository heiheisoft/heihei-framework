<?php 

namespace  modules\manage\api\controllers;

use yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;

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
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    protected function serializeData($data)
    {
        $data = Yii::createObject($this->serializer)->serialize($data);
        return is_array($data) && isset($data['code']) ? $data : $this->success($data);
    }

    /**
     * 失败输出
     */
    protected function fail($message, $errcode = null){
        $result = ['code'=>"FAIL",'message'=>$message];
        if($errcode !== null){
            $result['errcode'] = $errcode;
        }
        return $result;
    }

    /**
     * 成功输出
     */
    protected function success($data, $message = 'OK', $extends = null){
        $result = ['code'=>"SUCCESS",'message'=>$message];
        if(is_array($extends)){
            $result = array_merge($result, $extends);
        }
        if($data || is_array($data) || is_object($data)){
            $result['data'] = $data;
        }       
        return $result;
    }

    protected function queryToPages($query, $countQuery = null){
        if($countQuery == null){
            $countQuery = clone $query;
        }
        $pages = new Pagination([
            'pageSizeParam'=>'pagesize',
            'totalCount' => $countQuery->count(),
            'pageSizeLimit' => [1, 1000]
        ]);

        $pagesArr = [
            'page' => $pages->getPage() + 1,
            'pageCount' => $pages->getPageCount(),
            'totalCount' => 0 + $pages->totalCount,
            'pageSize' => $pages->getPageSize(),
            'offset' => $pages->offset,
            'limit' => $pages->limit
        ];

        $list = $query->offset($pages->offset)->limit($pages->limit)->all();
        return ['list'=>$list,'pages'=>$pagesArr];
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

?>