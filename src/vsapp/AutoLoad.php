<?php

 
namespace vsapp;

/**
 * Description of AutoLoad
 *
 * @author vench
 */
class AutoLoad {
    
    /**
     *
     * @var array string[]
     */
    private static $directoryes = [];

    /**
     * 
     */
    public static  function init() {
        $baseDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
        
        if(!in_array($baseDir, self::$directoryes)) {
            self::addDirectory($baseDir);
        }        
        
        spl_autoload_register(function ($class){
            if(class_exists($class, false)) {
                return;
            }
            
            $path = str_replace('\\', '/', $class);
            
            foreach(self::$directoryes as $baseDir) {
                $file = $baseDir . DIRECTORY_SEPARATOR . $path . '.php';
                if(file_exists($file)) {
                    include $file;
                    break;
                }                
            }    
        });
    }
    
    /**
     * 
     * @param string $directory
     */
    public static function addDirectory($directory) {
        array_push(self::$directoryes, $directory);
    }
}
