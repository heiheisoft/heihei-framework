<?php
namespace modules\manage\api\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use models\CronQueueJob;

/**
 * 计划任务
 */
class CronController extends BaseController
{
    use ClassDocCommentTrait;

    /**
     * 获取所有工作信息
     *
     * @return mixed
     */
    public function actionFetchJobs(){
        $types = ['minute','hour','day','month'];
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
     * 更新所有工作信息
     *
     * @parent cron/list
     * @return mixed
     */
    public function actionList(){
        $request = Yii::$app->getRequest();
        $types = ['minute','hour','day','month'];
        $query = CronQueueJob::find()->asArray()->where(['type'=>$types]);
        if($sortingby = $request->get('sortingby')){
            $query->orderBy($sortingby);
        }
        $list = $query->all();
        foreach ($list as $index => $item) {
            $list[$index]['status_text'] = CronQueueJob::statusText($item['status']);
        }
        return $list;
    }

    /**
     * 启动任务工作
     *
     * @return mixed
     */
    public function actionStart($class_name){
        CronQueueJob::start($class_name);
        return;
    }

    /**
     * 停止任务工作
     *
     * @return mixed
     */
    public function actionStop($class_name){
        CronQueueJob::stop($class_name);
        return;
    }
}
