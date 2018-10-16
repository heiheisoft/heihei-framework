<?php
namespace heihei\thread;

use \Thread as BaseThread;

/**
 * pthreads 基础类
 *
 */
class Pthreads extends BaseThread
{
    private $_callback;

    private $_callbackParams;

    public function __construct($callback, $callbackParams = []){
        $this->_callback = $callback;
        $this->_callbackParams = $callbackParams ?:[];
    }

    /**
     * 开始运行
     *
     */
    public function run() {
        //$this->synchronized(function(){           
           call_user_func_array($this->_callback, $this->_callbackParams);          
        //});
        
    }
}
