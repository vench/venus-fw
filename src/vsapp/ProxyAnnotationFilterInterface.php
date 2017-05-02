<?php
 

namespace vsapp;

/**
 *
 * @author vench
 */
interface ProxyAnnotationFilterInterface {

    function execBefore($object, $arguments);
    
    function execAfter($object, $result);
}
