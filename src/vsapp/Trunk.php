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
    
    const TYPE_LOG = 2;
    
    const TYPE_INIT_OBJECT = 4;
    
    
    
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
     * @param \vsapp\IObserver|\Closure $observer
     * @param int $type
     * @see addObserver()
     */
    public static function obs($observer, $type = 1) {
        self::getInstance()->addObserver($observer, $type);
    }
    
    
    public static function f(Event $event) {
        self::getInstance()->fire($event);
    }
    

    /**
     * 
     * @param \vsapp\IObserver|\Closure $observer
     * @param int $type
     */
    public function addObserver($observer, $type = 1) {
        if(!isset($this->observers[$type])) {
            $this->observers[$type] = [];
        }
        $this->observers[$type][] = $observer;
    }
    
    /**
     * 
     * @param \vsapp\Event $event
     */
    public function fire(Event $event) {
        
        $types = array_keys($this->observers);
        foreach($types as $type) {
            if(($event->getType() & $type) > 0) {
                foreach($this->observers[$type] as $observer) {
                    if($observer instanceof \Closure) {
                        call_user_func_array($observer, [$event]);
                    } else if($observer instanceof IObserver) {
                        $observer->fire($event);
                    }
                }
            }
        }
    }
    
     
}
