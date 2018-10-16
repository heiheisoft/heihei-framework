<?php
namespace models;

use Yii;

/**
 * 管理员授权表
 *
 * @author dejin <dejin@aliyun.com>
 */
class AuthAssignment extends ActiveRecord
{
    private $_assignments;

    /**
     * @inheritdoc
     */
    public function init(){
        $this->_assignments = [];
    }

    /**
     * 根据用户ID获取授权信息
     */
    public static function findOneByUserId($userId){
        return static::findOne($userId);
    }

    /**
     * 获取当前用户全部角色权限
     */
    public function getAllowPermissions(){
        $allowedPermissions = [];

        $roles = $this->getRoles();
        if(!empty($roles) && !empty($roleList = AuthRole::find()->select(['data'])->where(['id'=>$roles])->asArray()->all())){
            $rolePermissions = implode(',', array_column($roleList, 'data'));
            $allowedPermissions = array_filter(array_unique(explode(',', $rolePermissions)));
        }
        if($this->permissions){
            $allowedPermissions = array_merge($allowedPermissions, $this->permissions);
        }

        if($this->disallowedPermissions){
            $disallowedPermissions = $this->disallowedPermissions;
            $allowedPermissions = array_diff($allowedPermissions, $disallowedPermissions);
        }    
        return $allowedPermissions;
    }


    /**
     * 获取当前用户角色
     */
    public function getRoles(){
        if(isset($this->_assignments['role'])){
            return $this->_assignments['role'];
        }
        if($this->type == 'role'){
            $roles = $this->data;
        }
        else {
            $roles = static::find()->select(['data'])->asArray()->where(['type'=>'role','user_id'=>$this->user_id])->scalar();
        }
        $this->_assignments['role'] =  $roles ? array_filter(array_unique(explode(',', $roles))) : [];
        return $this->_assignments['role'];
    }

    /**
     * 获取当前用户权限
     */
    public function getPermissions(){
        if(isset($this->_assignments['permission'])){
            return $this->_assignments['permission'];
        }
        if($this->type == 'permission'){
            $permissions = $this->data;
        }
        else {
            $permissions = static::find()->select(['data'])->asArray()->where(['type'=>'permission','user_id'=>$this->user_id])->scalar();
        }
        $this->_assignments['permission'] = $permissions ? array_filter(array_unique(explode(',', $permissions))) : [];
        return $this->_assignments['permission'];
    }

    /**
     * 获取当前用户兼用权限
     */
    public function getDisallowedPermissions(){
        if(isset($this->_assignments['disallowed'])){
            return $this->_assignments['disallowed'];
        }
        if($this->type == 'disallowed'){
            $disallowed = $this->data;
        }
        else {
            $disallowed = static::find()->select(['data'])->asArray()->where(['type'=>'disallowed','user_id'=>$this->user_id])->scalar();
        }
        $this->_assignments['disallowed'] = $disallowed ? array_filter(array_unique(explode(',', $disallowed))) : [];
        return $this->_assignments['disallowed'];
    }

    

    /**
     * 获取角色标签名
     */
    public function getRoleTags(){
    	$roles = $this->getRoles();
        if(empty($roles)) return[];
    	$roleList = AuthRole::find()->select(['name'])->where(['id'=>$roles])->asArray()->all();
    	return array_column($roleList, 'name');
    }

    /**
     * 获取权限标签名
     */
    public function getPermissionTags(){
        $permissions = $this->getPermissions();
    	if(empty($permissions)) return[];
    	$permissionList = AuthPermission::find()->select(['name'])->where(['id'=>$permissions])->asArray()->all();
    	return array_column($permissionList, 'name');
    }

    /**
     * 获取禁用权限标签名
     */
    public function getDisallowedPermissionTags(){
        $disallowedPermissions = $this->getDisallowedPermissions();
    	if(empty($disallowedPermissions)) return[];
    	$disallowedList = AuthPermission::find()->select(['name'])->where(['id'=>$disallowedPermissions])->asArray()->all();
    	return array_column($disallowedList, 'name');
    }
}
