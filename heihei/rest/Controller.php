<?php
namespace heihei\rest;

use Yii;
use yii\rest\Controller AS BaseController;

/**
 * 基础控制器
 */
class Controller extends BaseController
{
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
}
