<?php

 

namespace vsapp;

/**
 * Description of Resource
 *
 * @author vench
 */
class Resource  implements ApplyAppableInterface , IResource{
    
    public function appInit(AppContextInterface $app) {
        
    }
    
    /**
     * 
     * @return string
     */
    public function getPath() {
         return dirname(__FILE__) . '/../resource';
    }

}
