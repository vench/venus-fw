<?php
 

namespace vsapp;

/**
 * Description of ProxyAnnotation
 *
 * @author vench
 */
class ProxyAnnotation implements ProxyAnnotationInterface {
    
    
    const PROXY_EXEC = '@proxy_exec';
     
    const PROXY_AFTER = 1; 
    
    const PROXY_BEFORE = 2;
    
 
    /**
     *
     * @var object
     */
    private $object;

    /**
     *
     * @var array 
     */
    private $executionMap;

    /**
     * 
     * @param Object $object
     * @param array $executionMap Description
     */
    private function __construct($object, $executionMap = []) {
        $this->object = $object;
        $this->executionMap = $executionMap;
    }
    
    
    /**
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        if(method_exists($this->object, $name)) { 
            $this->execBefore($name, $arguments);
            $result = call_user_func_array([$this->object, $name], $arguments);
            $this->execAfter($name, $result);
            return $result; 
        }
        throw new \Exception("Method {$name} not found");
    }
    
    /**
     * 
     * @param string $name
     * @param array $arguments
     * @return type
     */
    private function execBefore($name, $arguments) {
        if(!isset($this->executionMap[$name])) {
            return;
        }
        foreach($this->executionMap[$name] as $execution) {
            if(is_callable($execution)) {
                $execution($this->object, self::PROXY_BEFORE, $name, $arguments);
            } else if($execution instanceof ProxyAnnotationFilterInterface) {
                $execution->execBefore($this->object, $name, $arguments);
            }
        }
    }
    
    /**
     * 
     * @param string $name
     * @return type
     */
    private function execAfter($name, $result) {
        if(!isset($this->executionMap[$name])) {
            return;
        }
        foreach($this->executionMap[$name] as $execution) {
            if(is_callable($execution)) {
                $execution($this->object, self::PROXY_AFTER, $name, $result);
            } else if($execution instanceof ProxyAnnotationFilterInterface) {
                $execution->execAfter($this->object, $name, $result);
            }
        }
    }
    
    
    /**
     * 
     * 
     * @param object $object
     * @param \vsapp\Vendor $vendor
     * @return \vsapp\ProxyAnnotation
     */
    public static function createProxy($object, $vendor) {
        $executionMap = [];
        $ref = $vendor->getReflection( get_class( $object ) );
       
        foreach($ref->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method) {
            /* @var $method \ReflectionMethod */
            $doc = $method->getDocComment(); 
            if(!empty($doc) && preg_match_all('/\*\s+'.self::PROXY_EXEC.'\s+(\S+)\s/i', $doc, $maths) && isset($maths[1])) {
                foreach($maths[1] as $classname) {
                    if(!isset($executionMap[$method->getShortName()])) {
                        $executionMap[$method->getShortName()] = [];
                    }
                    $executionMap[$method->getShortName()][] = $vendor->get($classname);
                }
            } 
        }
        
        return new ProxyAnnotation($object, $executionMap);
        
    }
}
