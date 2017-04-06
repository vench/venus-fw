<?php

 
namespace vsapp;
 

/**
 * Description of AppConfig
 *
 * @author vench 
 */
class AppConfig implements ApplyAppableInterface {
 
    /**
     *
     * @var array 
     */
    private $config = [
        'defaultPage' => 'site',
    ];
    
    /**
     *
     * @var string 
     */
    private $resourcePath;




    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getValue($name, $default = null) {
        return  isset($this->config[$name]) ? $this->config[$name] : $default;
    }
    
    /**
     * 
     * @return array
     */
    public function getControllerPaths() {
        return array_merge( $this->getValue('controllerPaths', []), [ __NAMESPACE__ . '\controller']);
    }
    
    /**
     * 
     * @return string
     */
    public function getResourcePath() {
        return $this->resourcePath;
    }
    

    /**
     * 
     * @param \vsapp\AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) { var_dump( $app->get('resource')); exit();
       $this->resourcePath = $app->get('resource')->getPath();
       $path = $this->getResourcePath() . '/config';
       $scan = scandir( $path );
       foreach($scan as $file) {
           if($file == '.' || $file == '..') {
               continue;               
           }
           $data = require $path . DIRECTORY_SEPARATOR . $file;
           $this->config = array_merge($this->config, $data);
       }
    }
    
    
     

}
