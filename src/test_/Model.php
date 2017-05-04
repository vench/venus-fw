<?php
 

namespace test_;

/**
 * Description of Model
 *
 * @author vench
 */
class Model implements \vsapp\BindingInterface, \vsapp\proxy\AnnotationInterface {

    public $app;

    
    
    /**
     * @proxy_exec \test_\TimeExec   
     * @proxy_exec \test_\NameClassEcho
     */
    public function printName() {
        sleep(1);
        echo get_class($this);
    }

    /**
     * 
     * @return type
     */
    public function getBindMap() {
         
        return [
            'app' => '\vsapp\App',
        ];
    }

}
