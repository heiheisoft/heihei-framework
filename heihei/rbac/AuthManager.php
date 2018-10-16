<?php
namespace  heihei\rbac;

use yii\base\Component;
use yii\rbac\CheckAccessInterface;

/**
 * 权限管理
 */
class AuthManager extends Component implements CheckAccessInterface
{
    /**
     * @var 数组允许的权限
     */
	public $allowPermissions;

    /**
     * @var 字符串授权项分配的模型类名。 默认 "models\AuthAssignment".
     */
    public $assignmentModelClass = 'models\AuthAssignment';

    /**
     * @var 字符串权限列表模型类名。 默认 "models\AuthPermission".
     */
    public $permissionModelClass = 'models\AuthPermission';

	/**
	 * 检查当前用户当前权限是否允许操作
	 */
	public function checkAccess($userId, $permissionName, $params = [])
    {
    	if($this->allowPermissions && in_array($permissionName, $this->allowPermissions)){
            return true;
        }

        $modelClass = $this->assignmentModelClass;
        if(!($assignment = $modelClass::findOneByUserId($userId))){
            return false;
        }

        $permissionModelClass = $this->permissionModelClass;
        $permission = $permissionModelClass::findOne($permissionName);
        $permissionName = $permission && $permission->parent_id ? $permission->parent_id : $permissionName;

        //检查禁用权限
        $disallowedPermissions = $assignment->getDisallowedPermissions();
        if(in_array($permissionName, $disallowedPermissions)){
            return false;
        }
        $allowPermissions = $assignment->getAllowPermissions();
        return in_array('all', $allowPermissions) || in_array($permissionName, $allowPermissions);
    }
}