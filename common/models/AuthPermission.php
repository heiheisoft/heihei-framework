<?php
namespace models;

/**
 * 权限表
 *
 * @author dejin <dejin@aliyun.com>
 */
class AuthPermission extends ActiveRecord
{
	/**
     * 根据备注信息添加
     *
     * @return bool
     */
	public static function addByClassComment($data){
        $description = $data['description'];
        $name = isset($data['name']) ? $data['name'] : strchr($description, "\n", true) ?:$description;
        if(!empty($name)){ //最后一个字符只标点符号的话去掉
            $lastChar = mb_substr($name,-1,1,'utf-8');
            $lastCharOrd = ord($lastChar);
            $zhPunctuationRegex = "/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/";
            if($lastCharOrd > 32 && $lastCharOrd < 48 ||
                $lastCharOrd > 127 && preg_replace($zhPunctuationRegex,'',urlencode($lastChar)) == ''){
                $name = rtrim($name, $lastChar);
            }
        }

		$route = $data['route'];
        if($permission = self::find()->where(['id'=>$route])->one()){
            if(!empty($name)){
                $permission->name = trim($name);
            }
        }
        else{
            $permission = new self();
            $permission->id = $route;            
            $permission->name = trim($name);               
            //$privilege->description = $description; 
        }         
        $permission->parent_id = isset($data['parent']) ? $data['parent'] : '';
        return $permission->save();
    }
}
