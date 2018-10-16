<?php
namespace console\controllers;

use Yii;
use console\base\Controller;
use heihei\thread\Thread;
use models\CronQueueJob;
use yii\log\Logger;
use yii\base\UserException;
use yii\base\Event;

/**
 * 计划任务控制器
 */
class CronController extends Controller
{
    private $_envConfig;

    /**
     * 队列执行
     */
    public function actionQueue(){
        $jobNamespace = 'console\jobs\queue';
        $mutex = Yii::$app->get('mutex');
        if(!$mutex->acquire('queue')){
            $this->showMessage("服务已经被开启");
            return 0;
        }
        while (true) {
            $this->showMessage("开始执行{$jobNamespace}里的所有队列");        
            $this->runQueue($jobNamespace);
            sleep(1);
        }        
        $this->showMessage("");
    }

    /**
     * 队列方式执行命名空间下的所有工作
     */
    protected function runQueue($jobNamespace){
        $queue = Yii::$app->queue;
        $list = $queue->batchPop();
        $threads = [];
        $envs = $this->getEnvConfig();
        foreach ($list as $i=>$queueItem) {
            $jobName = $queueItem['key'];            
            $jobClassName = str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', ' ', $jobName))));
            $jobClassName = $jobNamespace . '\\' . $jobClassName . 'Job';
            $jobParams = $queueItem['value'];
            $jobParams = json_decode($jobParams, true);   
            if(!($job = $this->getJobInstance($jobClassName))){
                $queue->pushQueueFail($queueItem['queue_id'], "工作类:{$jobClassName}实例化失败！");
                continue;
            }
            $job->queueId = $queueItem['queue_id'];
            $threads[$i] = $this->runThreadJob(function($job, $params, $queueItem, $envs){
                if(!$job){
                    return;
                }
                $job->controller->initThreadEnv($envs);
                $queue = Yii::$app->queue;
                $startRunAt = time();
                try{                    
                    $job->run($params);
                    $queue->pushQueueSuccess($job->queueId);
                    $cronQueueParams = [
                        'last_run_at' => $startRunAt,
                        'max_run_duration' => time() - $startRunAt,
                        'max_run_duration_info' => json_encode($params)
                    ];
                    CronQueueJob::endRun($job->className(), $cronQueueParams);
                }
                catch(UserException $ex){
                    $queue->pushQueueFail($job->queueId, $ex->getMessage());
                }
            }, [$job, $jobParams, $queueItem, $envs]);

            if($threads[$i]){
                $threads[$i]->start(); 
            }
            else{
                unset($threads[$i]);
            }
            //$this->showMessage("{$jobName}线程启动成功！");
        }
        $this->showMessage("执行完成");
    }

    /**
     * 分钟执行
     */
    public function actionMinute(){
        $jobNamespace = 'console\jobs\minute';
        $mutex = Yii::$app->get('mutex');
        if(!$mutex->acquire('jobs-minute')){
            $this->showMessage("按分钟执行服务正在运行");
            return 0;
        }
        $this->showMessage("启动开始初始化！", true);
        $jobList = $this->getJobList('minute');
        $this->runJobs($jobList);
        $this->showMessage("");
    }

    /**
     * 小时执行
     */
    public function actionHour(){
        $mutex = Yii::$app->get('mutex');
        if(!$mutex->acquire('jobs-hour')){
            $this->showMessage("按天执行服务正在运行");
            return 0;
        }
        $this->showMessage("启动开始初始化！", true);
        $jobList = $this->getJobList('hour');
        $this->runJobs($jobList);
        $this->showMessage("");
    }

    /**
     * 按天执行
     */
    public function actionDay(){
        $jobNamespace = 'console\jobs\day';
        $mutex = Yii::$app->get('mutex');
        if(!$mutex->acquire('jobs-day')){
            $this->showMessage("按天执行服务正在运行");
            return 0;
        }
        $this->showMessage("启动开始初始化！", true);
        $jobList = $this->getJobList('day');
        $this->runJobs($jobList);
        $this->showMessage("");
    }

    /**
     * 运行单个任务
     */
    public function actionRunjob($jobName, $namespace = 'once'){
        $jobNamespace = 'console\\jobs\\' . $namespace;
        $this->showMessage("运行{$jobName}开始！\r\n");
        $this->job($jobNamespace . "\\{$jobName}Job");
        $this->showMessage("运行{$jobName}结束！\r\n");
    }

    /**
     * 执行命名空间下的所以工作
     */
    protected function runJobs($jobList){
        //var_dump('memory_get_usage:' . memory_get_usage(true) / 1024 / 1024);
        if(class_exists('Threaded')){
            $this->showMessage("包含类Thread");
        }
        else{
            $this->showMessage("Thread未包含！");
        }
        $this->showMessage("开始执行所有工作");
        $envs = $this->getEnvConfig();
        $threads = [];
        foreach ($jobList as $key=>$jobClass) {
            $this->showMessage("开始执行{$jobClass}！"); 
            $job = $this->getJobInstance($jobClass);  
            $threads[$key] = $this->runThreadJob(function($job, $envs){
                if(!$job){
                    return;
                }
                $job->controller->initThreadEnv($envs);
                $startRunAt = time();
                $job->run();
                $cronQueueParams = [
                    'last_run_at' => $startRunAt,
                    'max_run_duration' => time() - $startRunAt,
                    'max_run_duration_info' => ''
                ];
                $jobClass = $job->className();
                $job->controller->showMessage("工作{$jobClass}执行结束！");
                CronQueueJob::endRun($job->className(), $cronQueueParams);
            }, [$job, $envs]);
            if($threads[$key]){
                $threads[$key]->start(); 
            }
            else{
                unset($threads[$key]);
            }                  
        }

        //get_defined_constants
        //var_dump(get_defined_constants());
        //var_dump('memory_get_usage:' . memory_get_usage(true) / 1024 / 1024);
        //var_dump('memory_get_peak_usage:' . memory_get_peak_usage(true) / 1024 / 1024);
         
        $this->showMessage("所有执行完成");
    }

    protected function runThreadJob($callback, $args){
        $isSupportThread = Thread::isSupport();
        if(!$isSupportThread){
            call_user_func_array($callback, $args);     
            return;
        }
        return Thread::instance($callback, $args);
    }

    public function getEnvConfig(){
        if($this->_envConfig == null){
            $this->_envConfig = [
                'aliases' => Yii::$aliases,
                'classMap' => Yii::$classMap,
                'container' => new yii\di\Container(),

                'app' => Yii::$app,
                'globals' => $GLOBALS,

                'timeZone' => Yii::$app->getTimeZone()
            ];
        }
        return $this->_envConfig;
    }

    public function initThreadEnv($envs){
        if(isset($GLOBALS)){
            return;
        }        
        defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
        defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
        defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));
        defined('ISTHREAD') or define('ISTHREAD', true);

        $GLOBALS = $envs['globals'];
        $_SERVER = $GLOBALS['_SERVER'];
        //$vendorPath = $app->getVendorPath();
        
        spl_autoload_register(['Yii', 'autoload'], true, true);
        Yii::$app = $envs['app'];
        Yii::$aliases = $envs['aliases'];
        Yii::$classMap = $envs['classMap'];
        Yii::$container = $envs['container'];
        Yii::$app->setTimeZone($envs['timeZone']);
        //Yii::setLogger($controller->logger);                
        Event::offAll();
    }

    public function getJobInstance($jobClass){
        if(!class_exists($jobClass)){
            $this->logger->log("类${jobClass}找不到！", Logger::LEVEL_WARNING, 'console');
            return;
        }

        $job = Yii::createObject([
            'class' => $jobClass,
            'controller'=> $this,
            'logger' => $this->logger,
            'app' => Yii::$app
        ]);
        if (method_exists($job, 'run')) {
            $method = new \ReflectionMethod($job, 'run');
            if(!$method->isPublic()){
                $this->logger->log("类${jobClass}的方法run没有实现！",Logger::LEVEL_WARNING,'console');
                return false;
            }
        }
        else{
            $this->logger->log("类${jobClass}的方法run没有实现！",Logger::LEVEL_WARNING,'console');
            return false;
        }
        return $job;
    }

    /**
     * 获取所有工作
     */
    protected function getJobList($type = null){
        $list = CronQueueJob::find()->asArray()->select(['class_name'])->where(['type'=>$type,'status'=>'normal'])->all();
        if(!empty($list)){
            return array_column($list, 'class_name');  
        }        
        $jobNamespace = 'console\\jobs' . ($type ? '\\' . $type : '');
        $jobPath = Yii::getAlias('@' . str_replace('\\', DIRECTORY_SEPARATOR, $jobNamespace));
        if(!is_dir($jobPath)){
            return [];
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($jobPath, \RecursiveDirectoryIterator::KEY_AS_PATHNAME));
        $iterator = new \RegexIterator($iterator, '/.*Job\.php$/', \RecursiveRegexIterator::GET_MATCH);
        $jobs = [];
        foreach ($iterator as $matches) {
            $file = $matches[0];
            $relativePath = str_replace($jobPath, '', $file);
            $class = strtr($relativePath, [
                DIRECTORY_SEPARATOR => '\\',
                '.php' => '',
            ]);
            $jobClass = $jobNamespace . $class;
            if ($this->validateControllerClass($jobClass)) {
                $dir = ltrim(pathinfo($relativePath, PATHINFO_DIRNAME), DIRECTORY_SEPARATOR);
                $jobName = substr(basename($file), 0, -7);
                if (!empty($dir)) {
                    $jobName = $dir . DIRECTORY_SEPARATOR . $jobName;
                }
                $jobs[] = $jobClass;
            }
        }
        return $jobs;
    }

    /**
     * 验证类。
     *
     * @param string $controllerClass 控制器类名
     * @return bool
     */
    protected function validateControllerClass($jobClass)
    {
        if (class_exists($jobClass)) {
            $class = new \ReflectionClass($jobClass);
            return !$class->isAbstract();
        }

        return false;
    }
}
