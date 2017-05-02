<?php

namespace vsapp;

/**
 * Description of Vendor
 *
 * @author vench 
 * @todo deligate AutoLoad
 */
class Vendor implements AppContextInterface {

    /**
     *
     * @var Vendor 
     */
    private static $inst = null;

    /**
     *
     * @var array 
     */
    private $reflections = [];

    /**
     *
     * @var array 
     */
    private $mapClassNameAliases = [
        'resource' => __NAMESPACE__ . '\Resource',
        'config' => __NAMESPACE__ . '\AppConfig',
        'log' => __NAMESPACE__ . '\log\Log'
    ];

    /**
     * 
     */
    private function __construct() {
        
    }

    /**
     * 
     * @param string $className
     * @return \ReflectionClass
     * @throws Exception
     */
    public function getReflection($className) {
        if (!class_exists($className)) {
            throw new \Exception("Class {$className} not found");
        }
        if (!isset($this->reflections[$className])) {
            $this->reflections[$className] = new \ReflectionClass($className);
        }
        return $this->reflections[$className];
    }

    /**
     * 
     * @param string $className
     * @param boolean $newInstance
     * @return mixed
     */
    public function get($className, $newInstance = false) {
        $name = $this->getClassNameAliases($className);

        if (!isset($this->context[$name]) || $newInstance) {
            $ref = $this->getReflection($name);
            $inst = $ref->newInstanceArgs();
            Trunk::f(new Event(Trunk::TYPE_INIT_OBJECT, $inst));
            if ($ref->implementsInterface(__NAMESPACE__ . '\ApplyAppableInterface')) {
                call_user_func_array([$inst, 'appInit'], [$this]);
            }
            if ($ref->implementsInterface(__NAMESPACE__ . '\BindingInterface')) {
                $map = call_user_func_array([$inst, 'getBindMap'], [$this]);
                foreach($map as $fieldName => $className) {
                    $inst->{$fieldName} = $this->get($className);
                }
            }
            if ($ref->implementsInterface(__NAMESPACE__ . '\ProxyAnnotationInterface')) {
                $inst = ProxyAnnotation::createProxy($inst, $this); 
            }
            
            $this->context[$name] = $inst;
        }
        return $this->context[$name];
    }

    /**
     * 
     * @param string $aliases
     * @param string $className
     */
    public function setClassNameAliases($aliases, $className) {
        $this->mapClassNameAliases[$aliases] = $className;
    }

    /**
     * 
     * @param string $className
     * @return string
     */
    private function getClassNameAliases($className) {
        return isset($this->mapClassNameAliases[$className]) ?
                $this->mapClassNameAliases[$className] : $className;
    }

    
    /**
     * 
     * @return \vsapp\Vendor
     */
    public static function getInstance() {
        if(is_null(self::$inst)) {
            self::$inst = new Vendor();
        }
        return self::$inst;
    }
}
