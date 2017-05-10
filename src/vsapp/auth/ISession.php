<?php
 

namespace vsapp\auth;

/**
 *
 * @author vench
 */
interface ISession {

    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed Description
     */
    function get($name, $default = null);
    
    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    function set($name, $value);
}
