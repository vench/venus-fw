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
     * @var \vsapp\Vendor 
     */
    private $vendor;
    

    /**
     * 
     */
    public function __construct() { }

    
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
        $config = $this->get('config');
        
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
      
        $ref = $this->getVendor()->getReflection($controllerName); 
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
     * @return \vsapp\IResource
     */
    public function getResource() {   
        return $this->get('resource');
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
        return Vendor::getInstance()->get(__NAMESPACE__ . '\App');
    }

    /**
     * 
     * @param string $name class name or alias
     * @param boolean $newInstance
     * @return mixed
     */
    public function get($name, $newInstance = false) {
        return $this->getVendor()->get($name, $newInstance);
    }

    /**
     * 
     * @param type $aliases
     * @param type $className
     */
    public function setClassNameAliases($aliases, $className) {
        $this->getVendor()->setClassNameAliases($aliases, $className);
    }
    


    /**
     * 
     * @return \vsapp\Vendor
     */
    public function getVendor() {
        if(is_null($this->vendor)) {
            $this->vendor = Vendor::getInstance();
        }
        return $this->vendor; 
    }
}
