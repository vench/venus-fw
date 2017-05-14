<?php

 

namespace app;


use vsapp\IResource;

/**
 * Description of Resource
 *
 * @author vench
 */
class Resource implements IResource {
    
    public function getPath() {
        return dirname(__FILE__) . '/../resource';
    } 
}
