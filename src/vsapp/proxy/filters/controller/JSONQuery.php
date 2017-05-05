<?php
 
namespace vsapp\proxy\filters\controller;

/**
 * Description of JSONQuery
 *
 * @author vench
 */
class JSONQuery implements \vsapp\proxy\AnnotationFilterInterface {
    
    
    /**
     *
     * @var boolean 
     */
    public $jsonAsAssoc = false;
    
    /**
     *
     * @var string 
     */
    public $argumentNumber = '0';




    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $result
     */
    public function execAfter($object, $name, $result) { }

    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $arguments
     */
    public function execBefore($object, $name, &$arguments) {
        $data = file_get_contents('php://input'); 
        if(!is_array($arguments)) {
            $arguments = [];
        }
        $arguments[$this->argumentNumber] = $data ? json_decode($data, $this->jsonAsAssoc) : null;
         
    }

    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $exception
     */
    public function execException($object, $name, $exception) { }

}
