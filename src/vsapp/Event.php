<?php
 
namespace vsapp;

/**
 * Description of Event
 *
 * @author vench
 */
class Event {

    /**
     *
     * @var int
     */
    private $type = 1;
    
    /**
     *
     * @var mixed
     */
    private $context;


    /**
     * 
     * @param int $type
     */
    public function __construct($type, $context = null) {
        $this->type = $type;
        $this->context = $context;
    }
    
    /**
     * 
     * @return int
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getContext() {
        return $this->context;
    }


}
