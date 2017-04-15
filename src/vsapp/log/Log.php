<?php
 

namespace vsapp\log;

/**
 * Description of Log
 *
 * @author vench
 */
class Log {
    
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
        
        \vsapp\Trunk::getInstance()->fire(
                new \vsapp\Event( \vsapp\Trunk::TYPE_LOG, $this->stack));
        
        $this->stack = [];
        $this->flushed = false;
    }
}
