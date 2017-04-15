<?php
 

namespace vsapp;

/**
 * Description of Trunk
 *
 * @author vench 
 */
class Trunk {
  
    const TYPE_ALL = -1;
    
    const TYPE_INFO = 1;
    
    const TYPE_NOTICE = 2;
    
    const TYPE_IMPORTANT = 4;
    
    const TYPE_TOP = 8;
    
    const TYPE_LOG = 16;
    
    
    
    private $observers = [];
    
    /**
     *
     * @var Trunk
     */
    private static $instance = null;




    private function __construct() { }


    /**
     * 
     * @return Trunk
     */
    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 
     * @param type $observer
     */
    public function addObserver($observer, $type = 1) {
        if(!isset($this->observers[$type])) {
            $this->observers[$type] = [];
        }
        $this->observers[$type][] = $observer;
    }
    
    public function fire($event) {
        
        $types = array_keys($this->observers);
        foreach($types as $type) {
            if(($event->getType() & $type) > 0) {
                foreach($this->observers[$type] as $observer) {
                    if($observer instanceof \Closure) {
                        call_user_func_array($observer, [$event]);
                    }
                }
            }
        }
    }
    
     
}
