<?php

 

namespace test_;

/**
 * Description of NameClassEcho
 *
 * @author vench
 */
class NameClassEcho implements \vsapp\ProxyAnnotationFilterInterface {

    public function execAfter($object, $name, $result) {
        echo __METHOD__, '::', get_class($object), '<br>';
    }

    public function execBefore($object, $name, $arguments) {
        echo __METHOD__, '::', get_class($object), '<br>';
    }

    public function execException($object, $name, $exception) {
        
    }

}
