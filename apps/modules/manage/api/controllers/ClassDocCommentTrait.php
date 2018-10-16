<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\base\Application;
use yii\base\InlineAction;
use yii\base\InvalidParamException;
use yii\helpers\Inflector;

/**
 * 类的备注信息解析
 */
trait ClassDocCommentTrait
{    
    /**
     * 将注释块解析为数组。
     * @param \Reflector $reflection 待解析的类反射
     * @return array 解析后的数组
     */
    protected function parseDocCommentTags($reflection)
    {
        if(is_string($reflection)){
            $obj = new $className;
            $reflection = new \ReflectionClass($job);
        }
        if(!is_object($reflection)){
            throw new InvalidParamException("参数不对！");
            
        }
        else if(!($reflection instanceof \ReflectionClass) && !($reflection instanceof \ReflectionMethod)){
            $reflection = new \ReflectionClass($reflection);
        }
        $comment = $reflection->getDocComment();
        $comment = "@description \n" . strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))), "\r", '');
        $parts = preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);
        $tags = [];
        foreach ($parts as $part) {
            if (preg_match('/^(\w+)(.*)/ms', trim($part), $matches)) {
                $name = $matches[1];
                if (!isset($tags[$name])) {
                    $tags[$name] = trim($matches[2]);
                } elseif (is_array($tags[$name])) {
                    $tags[$name][] = trim($matches[2]);
                } else {
                    $tags[$name] = [$tags[$name], trim($matches[2])];
                }
            }
        }

        return $tags;
    }

    /**
     * 返回所有可用的控制器名
     *
     * @return array 所有控制器名
     */
    protected function getControllers()
    {
        $controllers = $this->getModuleControllers(Yii::$app);
        foreach (Yii::$app->getModules() as $module) {            
            $controllers = array_merge($controllers, $this->getModuleControllers($module));
        }
        sort($controllers);
        return array_unique($controllers);
    }

    /**
     * 返回模块下所有可用的控制名
     *
     * @param \yii\base\Module $module 模块实例
     * @return array 所有控制器名
     */
    protected function getModuleControllers($module){
        $prefix = $module instanceof Application ? '' : $module->getUniqueId() . '/';
        if(in_array($prefix, ['debug/', 'gii/'])){
            return [];
        }

        $controllers = [];
        foreach (array_keys($module->controllerMap) as $id) {
            $controllers[] = $prefix . $id;
        }

        $controllerNamespace =  $module->controllerNamespace;
        $controllerPath =  $module->getControllerPath();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllerPath, \RecursiveDirectoryIterator::KEY_AS_PATHNAME));
        $iterator = new \RegexIterator($iterator, '/.*Controller\.php$/', \RecursiveRegexIterator::GET_MATCH);
        foreach ($iterator as $matches) {
            $file = $matches[0];
            $relativePath = str_replace($controllerPath, '', $file);
            $class = strtr($relativePath, [
                DIRECTORY_SEPARATOR => '\\',
                '.php' => '',
            ]);
            if(in_array($class, ['\\BaseController', '\\SiteController'])){
                continue;
            }
            $controllerClass = $module->controllerNamespace . $class;
            if ($this->validateControllerClass($controllerClass)) {
                $dir = ltrim(pathinfo($relativePath, PATHINFO_DIRNAME), DIRECTORY_SEPARATOR);

                $controller = Inflector::camel2id(substr(basename($file), 0, -14), '-', true);
                if (!empty($dir)) {
                    $controller = $dir . DIRECTORY_SEPARATOR . $controller;
                }
                $controllers[] = $prefix . $controller;
            }
        }
        return $controllers;
    }

    /**
     * 验证类。
     *
     * @param string $controllerClass 控制器类名
     * @return bool
     */
    protected function validateControllerClass($controllerClass)
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            //return !$class->isAbstract() && $class->isSubclassOf('yii\base\Controller');
            return !$class->isAbstract();
        }

        return false;
    }

    /**
     * 返回控制器下所有可用的动作(action)
     *
     * @param Controller $controller 控制器实例
     * @return array 所有动作IDs.
     */
    protected function getActions($controller)
    {
        $actions = array_keys($controller->actions());
        $class = new \ReflectionClass($controller);
        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($name !== 'actions' && $method->isPublic() && !$method->isStatic() && strncmp($name, 'action', 6) === 0) {
                $actions[] = Inflector::camel2id(substr($name, 6), '-', true);
            }
        }
        sort($actions);

        return array_unique($actions);
    }

    /**
     * 获取控制器动作的反射
     *
     * @param Action $action
     * @return \ReflectionMethod
     */
    protected function getActionMethodReflection($controller, $action)
    {
        return $action instanceof InlineAction ? 
            new \ReflectionMethod($controller, $action->actionMethod) 
            : new \ReflectionClass($action);
    }   
}
