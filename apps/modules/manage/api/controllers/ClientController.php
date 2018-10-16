<?php
namespace modules\manage\api\controllers;

use Yii;
use models\Clients;
use yii\data\Pagination;

/**
 * 客户端管理
 */
class ClientController extends BaseController
{

    /**
     * 管理员列表
     *
     * @parent client/list
     * @return mixed
     */
    public function actionList(){
        $request = Yii::$app->getRequest();
        $query = Clients::find();       
        if(($fields = $request->get('fields'))){
           $query = $query->select($fields);
        }
        if($sortingby = $request->get('sortingby')){
            $query->orderBy($sortingby);
        }
        return $this->queryToPages($query);
    }
}
