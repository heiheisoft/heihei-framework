<?php
namespace heihei\thread;

use yii\base\BaseObject;

class Thread extends BaseObject
{
    private $_thread;

    /**
     * @var 判断是否支持多线程。
     */
    private static $_isSupport = null;

    /**
     * 创建多线程类实例
     */
    public static function instance($callback, $callbackParams = []){
        $thread = new static();
        $thread->_thread = new Pthreads($callback, $callbackParams);
        return $thread;
    }

    /**
     * 判断是否支持多线程
     */
    public static function isSupport(){
        if(self::$_isSupport === null){
            self::$_isSupport = (extension_loaded('pthreads') && class_exists('Threaded'));
        }
        return self::$_isSupport;
    }

    /**
     * 获取线程类实例
     */
    public function getThread(){
        return $this->_thread;
    }
    
    /**
     * 在独立线程中执行 run 方法
     */
    public function start() {
        return $this->_thread->start();
    }

    /**
     * 返回创建当前线程的线程ID
     */
    public function getCreatorId(){
        return $this->_thread->getCreatorId();
    }

    /**
     * 获取当前执行线程的引用
     */
    public static function getCurrentThread (){
        return $this->_thread->getCurrentThread();
    }

    /**
     * 返回当前执行线程的ID
     */
    public static function getCurrentThreadId (){
        return $this->_thread->getCurrentThreadId();
    }

    /**
     * 返回引用线程的ID
     */
    public function getThreadId(){
        return $this->_thread->getThreadId();
    }

    /**
     * 线程是否已经被加入（join）
     */    
    public function isJoined(){
        return $this->_thread->isJoined();
    }

    /**
     * 线程是否开始执行
     */ 
    public function isStarted(){
        return $this->_thread->isStarted();
    }

    /**
     * 让当前执行上下文等待被引用线程执行完毕
     */ 
    public function join(){
        return $this->_thread->join();
    }

    /**
     * 对象是否正在运行
     */ 
    public function isRunning(){
        return $this->_thread->isRunning();
    }

    /**
     * 检测是否因致命错误或未捕获的异常而导致执行过程异常终止
     */ 
    public function isTerminated (){
        return $this->_thread->isTerminated();
    }
}
