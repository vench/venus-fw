<?php
 

namespace vsapp\log;

/**
 * Description of Log
 *
 * @author vench
 */
class Log implements \vsapp\ApplyAppableInterface {
    
    /**
     *
     * @var array
     */
    private $stack = [];
    
    /**
     *
     * @var int
     */
    private $maxAutoFlush = 100;
    
    /**
     *
     * @var boolean
     */
    private $flushed = false;
    
    /**
     *
     * @var boolean
     */
    private $initObservers = false;


    /**
     *
     * @var array
     */
    private $logObservers = [
        '\vsapp\log\LogFile',
    ];
    
    /**
     *
     * @var \vsapp\AppContextInterface 
     */
    private $app;




    /**
     * 
     * @param string $message
     * @param int $type
     */
    public function addMessage($message, $type = -1) {
        $this->stack[] = [$message, $type, microtime(true)];
        
        if($this->maxAutoFlush === 0 || $this->maxAutoFlush <= count($this->stack)) {
            $this->flush();
        }
    }
    
    /**
     * 
     * @return type
     */
    public function flush() {
        if($this->flushed) {
            return;
        }
        
        $this->flushed = true;
        
        
        if(!$this->initObservers) {
            $this->initObservers();
        }
        
        \vsapp\Trunk::getInstance()->fire(
                new \vsapp\Event( \vsapp\Trunk::TYPE_LOG, $this->stack));
        
        $this->stack = [];
        $this->flushed = false;
    }

    /**
     * 
     * @param \vsapp\AppContextInterface $app
     */
    public function appInit(\vsapp\AppContextInterface $app) {
        $this->app = $app;
       
        //TODO \vsapp\Trunk::obs()
    }
    
    /**
     * 
     */
    public function __destruct() {
        $this->flush();
    }
    
    /**
     * 
     */
    private function initObservers() {
        if($this->initObservers) {
            $this->initObservers = false;
        }
        $this->initObservers = true;
        
        
        /* @var $appConfig \app\AppConfig */
        $appConfig = $this->app->get('config'); 
        $log = $appConfig->getValue('log');
        
        if(isset($log['logObservers'])) {
           $this->logObservers = $log['logObservers']; 
        }
        
        foreach ( $this->logObservers as $obsName) {
            $obs = $this->app->get($obsName);
            $obs->init();
        }
    }

}
