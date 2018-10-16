<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\HttpException;
use models\AuthPermission;



/**
 * 权限管理
 */
class AuthPermissionController extends BaseController
{    
	use ClassDocCommentTrait;
	
    /**
     * 权限列表
     *
     * @parent auth-permission/list
     * @return array
     */
    public function actionList(){
        $request = Yii::$app->getRequest();
        $type = $request->get('type');
        $query = AuthPermission::find()->asArray();
        if($type != 'all'){
           $query->where(['parent_id'=>'']); 
        }
        $query->orderBy(new Expression('CONCAT(parent_id,id) ASC'));
        return $query->all();
    }

    /**
     * 抓取所有权限
     *
     * @return mixed
     */
    public function actionFetchAuthPermission(){
        $controllers = $this->getControllers();
        $messages = [];
        foreach ($controllers as $route) {
            $result = Yii::$app->createController($route);
            list($controller, $actionID) = $result;
            $actions = $this->getActions($controller);
            if(!empty($actions)){
                $prefix = $controller->getUniqueId();
                foreach ($actions as $actionId) {
                    $action = $controller->createAction($actionId);
                    $actionReflection = $this->getActionMethodReflection($controller, $action);                
                    $tags = $this->parseDocCommentTags($actionReflection);
                    $route = $prefix . '/' . $actionId;
                    $tags['route'] = $route;
                    AuthPermission::addByClassComment($tags);
                }
            }  
        }
        return $messages ?:'操作成功';
    }

    /**
     * 权限添加
     *
     * @parent auth-permission/add
     * @return array
     */
    public function actionAdd(){
        $permission = new AuthPermission();
        return $this->save($permission);
    }

    /**
     * 权限修改
     *
     * @parent auth-permission/update
     * @return array
     */
    public function actionUpdate($id){
        $permission = AuthPermission::findOne($id);
        if(!$permission){
            return $this->fail("id:{$id} 找不到！");
        }
        return $this->save($permission);
    }

    /**
     * 权限删除
     *
     * @return array
     */
    public function actionDelete($id){
        $permission = AuthPermission::findOne($id);
        if($permission){
            $permission->delete();
            AuthPermission::deleteAll(['parent_id'=>$id]);
        }
        return;
    }

    protected function save($permission){
        $request = Yii::$app->getRequest();
        $post = $request->post();
        $permission->name = $request->post('name');
        if(!$permission->save()){
            return $this->fail($permission->getErrorMessage());
        }        
        return;
    }   
}
