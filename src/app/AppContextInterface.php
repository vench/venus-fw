<?php
 
namespace app;

/**
 *
 * @author vench
 */
interface AppContextInterface {
    
    /**
     * 
     * @param string $name
     * @param boolean $newInstance default false
     * @return mixed Description
     */
    function get($name, $newInstance = false);
}
