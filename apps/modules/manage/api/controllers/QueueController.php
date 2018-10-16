<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use models\CronQueueJob;
use models\QueueData;

/**
 * 消息队列
 */
class QueueController extends BaseController
{    
	use ClassDocCommentTrait;
	
    /**
     * 可执行的队列工作列表
     *
     * @parent queue/list
     * @return mixed
     */
    public function actionList(){
        $request = Yii::$app->getRequest();
        $types = ['queue'];
        $query = CronQueueJob::find()->asArray()->where(['type'=>$types]);
        if($sortingby = $request->get('sortingby')){
            $query->orderBy($sortingby);
        }
        return $query->all();
    }

    /**
     * 获取所有队列工作信息
     *
     * @return mixed
     */
    public function actionFetchJobs(){
        $types = ['queue'];
        $messages = '';
        foreach ($types as $type) {
            $jobNamespace = 'console\jobs\\' . $type;
            $actionPath = Yii::getAlias('@' . str_replace('\\', DIRECTORY_SEPARATOR, $jobNamespace));        
            if(!is_dir($actionPath)){
                continue;
            }
            $filesList = FileHelper::findFiles($actionPath,['only' => ['*Job.php']]);            
            foreach ($filesList as $fileName) {
                if(!($absoluteFileName = strchr($fileName,str_replace('\\', DIRECTORY_SEPARATOR, $jobNamespace),false))){
                    $messages = $messages . "文件:{$fileName}解析失败\r\n";
                    continue;
                }
                
                $className = str_replace(DIRECTORY_SEPARATOR, '\\', strchr($absoluteFileName, '.', true));
                if(!class_exists($className)){
                    $messages = $messages . "类名:{$className}找不到\r\n";
                    continue;
                }
                $reflection = new \ReflectionClass($className);
                $commentTags = $this->parseDocCommentTags($reflection);
                $commentTags['type'] = $type;
                CronQueueJob::addByClassComment($jobNamespace, $className, $commentTags);
            }
        }        
        return $messages ?:'操作成功';
    }

    /**
     * 消息队列出错列表.
     *
     * @return string
     */
    public function actionErrorsList()
    {
        $request = Yii::$app->getRequest();
        $query = QueueData::find()->asArray()->where(['status'=>2]);
        $sortingby = $request->get('sortingby') ?: 'queue_id DESC';
        $query->orderBy($sortingby);
        $result = $this->queryToPages($query);
        return $result;
    }
}
