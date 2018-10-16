<?php 
namespace  modules\rest\queue;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\base\Application;

class Module extends BaseModule implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
	public function init(){
        parent::init();
        if(Yii::$app->state == Application::STATE_INIT){
            return;
        }
        if(isset($components['errorHandler'])){
            Yii::$app->getErrorHandler()->unregister();
            Yii::$app->set('errorHandler', $components['errorHandler']);
            Yii::$app->getErrorHandler()->register();
        }
        unset($components['errorHandler']);
        foreach ($components as $name => $config) {
        	Yii::$app->set($name, $config);
        }
        Yii::$app->user->loginUrl = null;
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'queue' => $this->id.'/queue',
            ['pattern' => 'queue/<action>', 'route' => $this->id.'/queue/<action>']
        ]);
    }
}
?>