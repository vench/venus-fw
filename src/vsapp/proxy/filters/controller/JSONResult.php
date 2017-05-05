<?php
 
namespace vsapp\proxy\filters\controller;

use vsapp\proxy\AnnotationFilterInterface;

/**
 * Description of JSONResult
 *
 * @author vench
 */
class JSONResult implements AnnotationFilterInterface {
    
    
    /**
     *
     * @var boolean exit app after render result
     */
    public $autoExit = false;
    
    /**
     *
     * @var boolean exit if catch exeption 
     */
    public $catchException = true;



    /**
     * 
     * @param object $object
     * @param string $name
     * @param mixed $result
     */
    public function execAfter($object, $name, $result) {
        $this->render($result); 
    }

    /**
     * 
     * @param object $object
     * @param string $name
     * @param array $arguments
     */
    public function execBefore($object, $name, &$arguments) { }
    
    /**
     * 
     * @param type $object
     * @param type $name
     * @param \Exception $exception
     */
    public function execException($object, $name, $exception) {
        
        $this->autoExit = $this->catchException;
        
        $this->render([
            'code'      => $exception->getCode(),
            'message'   => $exception->getMessage(),
        ]);
    }
    
    /**
     * 
     * @param mixed $data
     */
    private function render($data) {
        header('Content-type: application/json');
        echo json_encode($data);
        
        if($this->autoExit) { 
           exit(0); 
        }
    }
 
}
