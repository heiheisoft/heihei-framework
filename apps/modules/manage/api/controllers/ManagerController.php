<?php
namespace modules\manage\api\controllers;

use Yii;
use models\Manager;
use models\AuthAssignment;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * 管理员管理
 */
class ManagerController extends BaseController
{

    /**
     * 管理员列表
     *
     * @parent manager/list
     * @return mixed
     */
    public function actionList(){
        $request = Yii::$app->getRequest();
        $query = Manager::find();       
        if(!($fields = $request->get('fields'))){
            $columns = Manager::getTableSchema()->columns;
            unset($columns['password']);
            $fields = array_keys($columns);
        }
        if($username = $request->get('username')){
            $query->andWhere(['username'=>$username]);
        }
        if($mobile = $request->get('mobile')){
            $query->andWhere(['mobile'=>$mobile]);
        }
        if($full_name = $request->get('full_name')){
            $query->andWhere(['LIKE', 'full_name', $full_name]);
        }
        if($email = $request->get('email')){
            $query->andWhere(['email'=>$email]);
        }
        if($sortingby = $request->get('sortingby')){
            $query->orderBy($sortingby);
        }
        $query->asArray();
        $result = $this->queryToPages($query);
        $list = $result['list'];
        foreach ($list as $index => $item) {
            $result['list'][$index]['status_text'] = Manager::statusText($item['status']);
        }
        return $result;
    }

    /**
     * 添加管理员.
     *
     * @parent manager/add
     * @return string
     */
    public function actionAdd()
    {
        $manager = new Manager();
        return $this->save($manager);
    }

    /**
     * 修改管理员.
     *
     * @parent manager/update
     * @return string
     */
    public function actionUpdate($id)
    {
        $manager = Manager::findOne($id);
        if(!$manager){
             return $this->fail("ID:{$id} 找不到！");
        }
        return $this->save($manager); 
     
    }

    /**
     * 删除管理员
     *
     */
    public function actionDelete($id){
        $manager = Manager::findOne($id);
        if($manager){
            $manager->delete();
        }
        return;
    }

    /**
     * 保存数据
     *
     */
    protected function save($manager){
        $request = Yii::$app->getRequest();
        $post = $request->post();
        $manager->username = $request->post('username');
        $manager->full_name = $request->post('full_name');
        $manager->setPassword($request->post('password'));

        $manager->mobile = $request->post('mobile', '');
        $manager->email = $request->post('email', '');
        $manager->status = $request->post('status',0);

        if(!$manager->save()){
            return $this->fail($manager->getErrorMessage());
        }

        
        $authAssignmentList = $manager->getAuthAssignmentAll();        
        if(!empty($authAssignmentList)){
            $authAssignmentList = ArrayHelper::index($authAssignmentList, 'type');
        }        
        $assignmentTypes = ['role'=>'roles','permission'=>'permissions','disallowed'=>'disallowed_permissions'];
        foreach ($assignmentTypes as $assignmentType=>$postKey) {
            if($authAssignmentList && isset($authAssignmentList[$assignmentType])){
                $assignment = $authAssignmentList[$assignmentType];
            }
            else{
                $assignment = new AuthAssignment;
                $assignment->type = $assignmentType;
                $assignment->user_id = $manager->id;
            }
            $assignment->data = $request->post($postKey, '');
            if(empty($assignment->data) && $assignment->getIsNewRecord()){
                continue;
            }
            if(!$assignment->save()){
                return $this->fail($assignment->getErrorMessage());
            }
        }
        return true;
    }

    /**
     * 管理员密码重置
     *
     * @return string
     */
    public function actionResetPassword($id){
        $manager = Manager::findOne($id);
        if(!$manager){
             return $this->fail("ID:{$id} 找不到！");
        }
        $newPassword = uniqid();
        $manager->setPassword($newPassword);
        if(!$manager->save()){
            return $this->fail($manager->getErrorMessage());
        }
        return "新密码是:{$newPassword}";
    }
    
}
