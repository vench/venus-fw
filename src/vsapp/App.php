<?php

namespace vsapp;

/**
 * Description of App
 *
 * @author vench
 */
class App implements AppContextInterface {
    
    /**
     *
     * @var App 
     */
    private static $inst = null;
    
    
    
    /**
     *
     * @var array 
     */
    private $context = [];
    
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
        'resource'  => __NAMESPACE__ . '\Resource',
    ];



    /**
     * 
     */
    private function __construct() { }

     
    /**
     * 
     */
    public function run() { 
        $this->runRequest();  
    }

    
    /**
     * 
     * @throws \Exception 
     */ 
    private function runRequest() { 
        $request = $this->get(__NAMESPACE__ . '\Request');
        $config = $this->get(__NAMESPACE__ . '\AppConfig');
        
        $action = $request->getAction();
        $path = explode('/', $action); 
        
        $controllerName = isset($path[0]) ? 
                __NAMESPACE__ . '\controller\\' .ucfirst($path[0]).'Controller' :  __NAMESPACE__ . '\controller\SiteController';
        foreach ($config->getControllerPaths() as $pathController) {
            $pathController = rtrim($pathController, '\\') . '\\' .ucfirst($path[0]).'Controller';
            if(class_exists($pathController )) {
                $controllerName = $pathController;
                break;
            }
        }
        
        $method = isset($path[1]) ? 'action'.ucfirst($path[1]) : 'actionIndex'; 
      
        $ref = $this->getReflection($controllerName); 
        if(!$ref->hasMethod($method)) {
            throw new \Exception("Action [$method] not found!");
        }
        $refMethod = $ref->getMethod($method);
        if(!$refMethod->isPublic()) {
            throw new \Exception("Method [$method] not is public!");
        }
        $params = [];
        foreach($refMethod->getParameters() as $param) {
            $params[$param->name] = $request->get($param->name);
        }
        $controller = $this->get($ref->getName());         
        call_user_func_array([$controller, $method], $params);
    }
    
    /**
     * 
     * @param string $className
     * @return \ReflectionClass
     * @throws Exception
     */
    private function getReflection($className) {
        if(!class_exists($className)) {
            throw new \Exception("Class {$className} not found");
        }
        if(!isset($this->reflections[$className])) {
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
    
        if(!isset($this->context[$name]) || $newInstance) {
            $ref = $this->getReflection($name);
            $inst = $ref->newInstanceArgs(); 
            if($ref->implementsInterface( __NAMESPACE__ . '\ApplyAppableInterface')) {
                call_user_func_array([$inst, 'appInit'], [$this]);
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
     * @return \vsapp\IResource
     */
    public function getResource() {   
        return $this->get('resource');
    }
    
    /**
     * 
     * @param string $className
     * @return string
     */
    private function getClassNameAliases($className) {         
        return isset($this->mapClassNameAliases[$className]) ? 
            $this->mapClassNameAliases[$className]: $className; 
    }


    /**
     * 
     */
    public static function launch() {
        if(is_null(self::$inst)) {
            $app = self::current();
            $app->run();
        } 
    }

    /**
     * 
     * @return App
     */
    public static function current() {
        if(is_null(self::$inst)) {
            self::$inst = new self(); 
        } 
        return self::$inst;
    }
    

}
