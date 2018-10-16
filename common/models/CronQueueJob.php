<?php
namespace models;

/**
 * 任务队列工作
 *
 * @author dejin <dejin@aliyun.com>
 */
class CronQueueJob extends ActiveRecord
{
    /**
     * 任务队列工作
     *
     */
    public static function addByClassComment($baseNamespace, $className, $commentTags){
        if($cronQueueJob = self::find()->where(['class_name'=>$className])->select(['class_name'])->asArray()->one()){
            return true;
        }
        $description = $commentTags['description'];
        $jobname = '';
        if(strpos($description,"\n") === false){
            $jobname = $description;
        }
        $cronQueueJob = new self();
        $cronQueueJob->class_name = $className;
        $cronQueueJob->job_name = isset($commentTags['name']) ? $commentTags['name'] : $jobname;
        $cronQueueJob->run_name = substr(str_replace([$baseNamespace . '\\'], '', $className), 0 , -3);  
        if(strtolower($cronQueueJob->run_name) == 'base'){
            return true;
        }              
        $cronQueueJob->namespace = $baseNamespace;
        $cronQueueJob->type = $commentTags['type'];
        $cronQueueJob->author = isset($commentTags['author']) ? $commentTags['author'] : '';           
        $cronQueueJob->description = $description;
        return $cronQueueJob->save();
    }

    /**
     * 启动任务工作
     *
     */
    public static function start($jobClassName){
        $cronQueueJob = CronQueueJob::find()->where(['class_name'=>$jobClassName])->one();
        if(!$cronQueueJob){
            return false;
        }
        $cronQueueJob->status = 'normal';
        return $cronQueueJob->save();
    }

    /**
     * 停止任务工作
     *
     */
    public static function stop($jobClassName){
        $cronQueueJob = CronQueueJob::find()->where(['class_name'=>$jobClassName])->one();
        if(!$cronQueueJob){
            return false;
        }
        $cronQueueJob->status = 'stop';
        return $cronQueueJob->save();
    }

    /**
     * 记录任务运行情况
     *
     */
    public static function endRun($jobClassName, $params){
        $cronQueueJob = CronQueueJob::find()->where(['class_name'=>$jobClassName])->one();
        if(!$cronQueueJob){
            return false;
        }
        $cronQueueJob->last_run_at = $params['last_run_at'];
        if($params['max_run_duration'] > $cronQueueJob->max_run_duration){
            $cronQueueJob->max_run_duration = $params['max_run_duration'];
            $cronQueueJob->max_run_duration_info = $params['max_run_duration_info'];
        }
        $cronQueueJob->save();
    }  

    public static function statusText($status = 0){
        $statusTexts = ['stop'=>'禁用', 'normal'=>'正常'];
        return isset($statusTexts[$status]) ? $statusTexts[$status] : '未知';
    }  
}