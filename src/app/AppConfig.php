<?php

 
namespace app;

use \app\ApplyAppableInterface;

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
        'defaultPage' => 'page',
    ];
    
    
    
    
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
        return array_merge( $this->getValue('controllerPaths', []), ['app\controller']);
    }

    /**
     * 
     * @param \app\AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) {
       $path = self::getPath(); 
       $scan = scandir( $path );
       foreach($scan as $file) {
           if($file == '.' || $file == '..') {
               continue;               
           }
           $data = require $path . DIRECTORY_SEPARATOR . $file;
           $this->config = array_merge($this->config, $data);
       }
    }
    
    
    /**
     * 
     * @return string
     * @todo ТОже надо тянуть через конфиг
     */
    public static function getPath() {
        return dirname(__FILE__) . '/../resource/config';
    }

}
