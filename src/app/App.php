<?php

namespace app;

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
     */
    private function __construct() { }

     
    /**
     * 
     */
    private function run() { 
        $this->runRequest();  
    }

    
    /**
     * 
     * @throws \Exception
     * @todo add directory app\controller
     */ 
    private function runRequest() { 
        $request = $this->get('app\Request');
        $condif = $this->get('app\AppConfig');
        
        $action = $request->getAction();
        $path = explode('/', $action);
        
        
        
        
        
        $controllerName = isset($path[0]) ? 
                'app\controller\\' .ucfirst($path[0]).'Controller' : 'app\controller\HomeController';
        foreach ($condif->getControllerPaths() as $pathController) {
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
     * @param type $name
     * @param type $newInstance
     * @return type
     */
    public function get($name, $newInstance = false) {
        if(!isset($this->context[$name]) || $newInstance) {
            $ref = $this->getReflection($name);
            $inst = $ref->newInstanceArgs(); 
            if($ref->implementsInterface('app\ApplyAppableInterface')) {
                call_user_func_array([$inst, 'appInit'], [$this]);
            }
            $this->context[$name] = $inst;
        }
        return $this->context[$name];
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
