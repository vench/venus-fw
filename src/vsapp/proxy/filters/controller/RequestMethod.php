<?php
 

namespace vsapp\proxy\filters\controller;

/**
 * Description of RequestMethod
 *
 * @author vench
 */
class RequestMethod implements \vsapp\proxy\AnnotationFilterInterface {
    
    /**
     *
     * @var array
     */
    public $types = [];




    public function execAfter($object, $name, $result) { }

    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $arguments
     * @throws \Exception 
     */
    public function execBefore($object, $name, &$arguments) { 
        
        if(empty($this->types)) {
            return;
        }
        
        $method = strtoupper( filter_input(INPUT_SERVER, 'REQUEST_METHOD') );
        $types = array_map('strtoupper', $this->types);
        if(!in_array($method,  $types)) { 
            throw new \Exception("Request method only [".join(', ', $types)."]", 400);
        }
    }

    public function execException($object, $name, $exception) {
        
    }

}
