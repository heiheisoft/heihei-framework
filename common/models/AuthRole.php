<?php
namespace models;

use Yii;
/**
 * 角色
 *
 * @author dejin <dejin@aliyun.com>
 */
class AuthRole extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'data' => '权限列表'
        ];
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'filter', 'filter' => 'trim'],
            [['name','data'], 'required'],
            [['name'], 'unique']
        ];
    }
}
