<?php
 

namespace vsapp\proxy;

/**
 * Description of ProxyAnnotation
 *
 * @author vench
 */
class Annotation implements AnnotationInterface {
    
    
    const PROXY_EXEC = '@proxy_exec';
     
    const PROXY_AFTER = 1; 
    
    const PROXY_BEFORE = 2;
    
    const PROXY_EXCEPTION = 3;
    
 
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
            
            try {
                $this->execBefore($name, $arguments);
                $result = call_user_func_array([$this->object, $name], $arguments);
                $this->execAfter($name, $result);
            } catch (\Exception $e) {
                $this->execException($name, $e);
                throw  $e;
            }    
            
            
            return $result; 
        }
        throw new \Exception("Method {$name} not found");
    }
    
    /**
     * 
     * @param type $name
     * @param \Exception $e
     * @return type
     */
    private function execException($name, \Exception $e) {
        if(!isset($this->executionMap[$name])) {
            return;
        } 
        foreach($this->executionMap[$name] as $execution) {
            if(is_callable($execution)) {
                $execution($this->object, self::PROXY_EXCEPTION, $name, $e);
            } else if($execution instanceof AnnotationFilterInterface) {
                $execution->execException($this->object, $name, $e);
            }
        }
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
            } else if($execution instanceof AnnotationFilterInterface) {
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
            } else if($execution instanceof AnnotationFilterInterface) {
                $execution->execAfter($this->object, $name, $result);
            }
        }
    }
    
    
    /**
     * 
     * 
     * @param object $object
     * @param \vsapp\Vendor $vendor
     * @return \vsapp\proxy\Annotation
     */
    public static function createProxy($object, $vendor) {
        $executionMap = [];
        $ref = $vendor->getReflection( get_class( $object ) );
        $pm = '/\*\s+'.self::PROXY_EXEC.'\s+(\S+)\s+(\{.*\}|)/i'; 
        
        foreach($ref->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method) {
            /* @var $method \ReflectionMethod */
            $doc = $method->getDocComment(); 
            
            if(!empty($doc) && preg_match_all($pm, $doc, $maths) && isset($maths[1])) {
                
                foreach($maths[1] as $n => $classname) {
                    if(!isset($executionMap[$method->getShortName()])) { 
                        $executionMap[$method->getShortName()] = [];
                    }
                   
                    $inst = $vendor->get($classname, true);
                    if(isset($maths[2][$n]) && ($jsonAssoc = json_decode($maths[2][$n], true))) {
                         foreach($jsonAssoc as $field => $value) {
                             $inst->{$field} = $value;
                         }
                             
                    }
                    $executionMap[$method->getShortName()][] = $inst;
                }
            } 
        }
        
        return new Annotation($object, $executionMap);
        
    }
}
