<?php

 

namespace vsapp\proxy\filters\controller;

/**
 * Description of ControllerAccess
 *
 * @author vench
 * @todo in progress
 */
class Access implements \vsapp\proxy\AnnotationFilterInterface, \vsapp\ApplyAppableInterface {
    
    
    /**
     *
     * @var \vsapp\auth\WebClient 
     */
    private $webClient;



    /**
     * 
     * @param object $object
     * @param string $name
     * @param mixed $result
     */
    public function execAfter($object, $name, $result) { }

    /**
     * 
     * @param object $object
     * @param string $name
     * @param array $arguments
     */
    public function execBefore($object, $name, &$arguments) {
         $id = $this->makeId($object, $name);
         $this->checkAccess($id);
    }

    /**
     * 
     * @param object $object
     * @param string $name
     * @param \Exception $exception
     */
    public function execException($object, $name, $exception) { }
    
    /**
     * 
     * @param string $id
     * @throws \Exception
     */
    private function checkAccess($id) { 
        if(!$this->webClient->checkAccess($id)) {
            throw new \Exception('Permission denied for '.$id.'', 403);
        }
    }
    
    /**
     * 
     * @param type $object
     * @param type $name
     * @return type
     */
    private function makeId($object, $name) {
        $className =  get_class($object);
        $id = strtolower( substr($className, strrpos($className, '\\')+1). '.' . $name);
        return $id;
    }

    /**
     * 
     * @param \vsapp\AppContextInterface $app
     */
    public function appInit(\vsapp\AppContextInterface $app) {
        $this->webClient = $app->get('webClient');
    }

}
