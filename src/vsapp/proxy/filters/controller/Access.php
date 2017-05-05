<?php

 

namespace vsapp\proxy\filters\controller;

/**
 * Description of ControllerAccess
 *
 * @author vench
 * @todo in progress
 */
class Access implements \vsapp\proxy\AnnotationFilterInterface {
    
    
    
    
    public function execAfter($object, $name, $result) {
        
    }

    public function execBefore($object, $name, &$arguments) {
         $id = $this->makeId($object, $name);
         $this->checkAccess($id);
    }

    public function execException($object, $name, $exception) {
        
    }
    
    private function checkAccess($id) {
        $accessList = [];
        if(!in_array($id, $accessList)) {
            throw new \Exception('Permission denied', 403);
        }
    }
    
    
    private function makeId($object, $name) {
        $className =  get_class($object);
        $id = strtolower( substr($className, strrpos($className, '\\')+1). '.' . $name);
        return $id;
    }
}
