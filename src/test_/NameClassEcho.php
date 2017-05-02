<?php

 

namespace test_;

/**
 * Description of NameClassEcho
 *
 * @author vench
 */
class NameClassEcho implements \vsapp\ProxyAnnotationFilterInterface {

    public function execAfter($object, $result) {
        echo __METHOD__, '::', get_class($object), '<br>';
    }

    public function execBefore($object, $arguments) {
        echo __METHOD__, '::', get_class($object), '<br>';
    }

}
