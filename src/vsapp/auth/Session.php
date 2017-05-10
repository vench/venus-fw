<?php

 

namespace vsapp\auth;


use \vsapp\ApplyAppableInterface;
use \vsapp\AppContextInterface;


/**
 * Description of Session
 *
 * @author vench
 */
class Session implements ISession , ApplyAppableInterface {
    
    
    /**
     *
     * @var boolean
     */
    private $isStart = false;


    /**
     *
     * @var string
     */
    private $sessionName = 'VSAppSessionName';




    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null) {
        $nkey = $this->makeKey($name);
        return isset($_SESSION[$nkey]) ? $_SESSION[$nkey] : $default;
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value) {
        $nkey = $this->makeKey($name);
        $_SESSION[$nkey] = $value;
    }
    
    
    /**
     * 
     * @return void
     */
    public function start() {
        if($this->isStart) {
            return;
        }
        
        session_name($this->sessionName);
        session_start();
    }
    
    
    /**
     * 
     * @param string $name
     * @param string $salt
     * @return string
     */
    private function makeKey($name, $salt = null) {
        if(is_null($salt)) {
            $salt = __METHOD__;
        }
        return md5($name . $salt);
    }

    
    /**
     * 
     * @param AppContextInterface $app
     * @TODO
     */
    public function appInit(AppContextInterface $app) {
        
        
        
        $this->start();
    }

}
