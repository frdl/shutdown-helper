<?php
/*
MIT License

Copyright (c) 2019 Webfan Homepagesystem
*/

namespace frdlweb\Thread;
class ShutdownTasks {
    protected $callbacks; 
    protected static $instance = null; 

    public function __construct() {
        $this->callbacks = [];
		register_shutdown_function([$this, 'callRegisteredShutdown']);
    }
	
	public function __invoke(){
		return call_user_func_array([$this,'registerShutdownEvent'], func_get_args() ); 
	}
	
	public function __call($name, $params){
		if('clear'===$name){
			$this->callbacks = [];
			return $this;
		}
		
		throw new \Exception('Unhandled metod in '.__METHOD__.' '.basename(__FILE__).' '.__LINE__);
	}	
	
	public static function __callStatic($name, $params){
		return call_user_func_array([self::getInstance(),$name], $params ); 
	}
	
    public static function getInstance() {
             if(null===self::$instance){
			    	self::$instance = new self; 
			 }
		
		return self::$instance;
    }
	
    public static function mutex() {
 		return self::getInstance();
    }
	
    public function registerShutdownEvent() {
        $callback = func_get_args();
       
        if (empty($callback)) {
			throw new \Exception('No callback passed to '.__METHOD__);
        }
        if (!is_callable($callback[0])) {
			throw new \Exception('Invalid callback passed to '.__METHOD__);
        }
        $this->callbacks[] = $callback;
		
        return $this;
    }
	
    public function prepend() {
        $callback = func_get_args();
       
        if (empty($callback)) {
			throw new \Exception('No callback passed to '.__METHOD__);
        }
        if (!is_callable($callback[0])) {
			throw new \Exception('Invalid callback passed to '.__METHOD__);
        }
        array_unshift($this->callbacks, $callback);
		
        return $this;
    }
	
    public function callRegisteredShutdown() {
		while(0<count($this->callbacks)){
		  	$arguments = array_shift($this->callbacks);
			$callback = array_shift($arguments);
		    call_user_func_array($callback, $arguments);				
		}
    }

}
