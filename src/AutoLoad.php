<?php

 

/**
 * Description of AutoLoad
 *
 * @author vench
 */
class AutoLoad {
    /**
     * 
     */
    public static  function init() {
        $baseDir = dirname(__FILE__);
        spl_autoload_register(function ($class) use(&$baseDir) {
            if(class_exists($class, false)) {
                return;
            }
            $path = str_replace('\\', '/', $class);
            $file = $baseDir . DIRECTORY_SEPARATOR . $path . '.php';
            if(file_exists($file)) {
                include $file;
            }    
        });
    }
}
