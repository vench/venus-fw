<?php
 

namespace vsapp;

/**
 *
 * @author vench
 */
interface ProxyAnnotationFilterInterface {

    /**
     * 
     * @param object $object
     * @param string $name
     * @param array $arguments
     */
    function execBefore($object, $name, $arguments);
    
    /**
     * 
     * @param object $object
     * @param string $name
     * @param array $result
     */
    function execAfter($object, $name, $result);
    
    /**  
     * @param object $object
     * @param string $name
     * @param \Exception $exception
     */
    function execException($object, $name, $exception);
}
