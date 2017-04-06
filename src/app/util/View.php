<?php

namespace app\util;

/**
 * Description of View
 *
 * @author vench
 */
class View {
    
    /**
     *
     * @var array 
     */
    static private $staticContent = [];
    
    /**
     * 
     * @param string $key
     * @param string $value html text
     * @param boolean $reset Description
     */
    public static function addStaticContent($key, $value, $reset = false) {
        if(!$reset && isset(self::$staticContent[$key])) {
            self::$staticContent[$key] .= $value;
        } else {
            self::$staticContent[$key] = $value;
        }
    }
    
    /**
     * 
     * @param string $key
     * @return string
     */
    public static function getStaticContent($key) {
        return isset(self::$staticContent[$key]) ? self::$staticContent[$key] : '';
    }
    

    /**
     * 
     * @return string
     * @todo ТОже надо тянуть через конфиг
     */
    public static function getPathView() {
        return dirname(__FILE__) . '/../../resource/views';
    }

    /**
     * 
     * @param type $file
     * @param type $params
     * @param type $output
     * @return type
     * @throws \Exception
     */
    public static function renderPhp($file, $params = array(), $output = true) {

        $path = self::getPathView() . DIRECTORY_SEPARATOR . $file;
        if (strpos($path, '.php') === FALSE) {
            $path .= '.php';
        }
        if (!file_exists($path)) {
            throw new \Exception("Not exists file view [{$file}, {$path}]");
        }

        ob_start();
        extract($params);
        extract(self::getHelperFunstions());
        include_once($path);
        $s = ob_get_contents();
        ob_end_clean();

        if ($output) {
            echo $s;
        }
        return $s;
    }
    
    /**
     * 
     * @param mixed $params
     * @param boolean $addHead
     * @param boolean $terminate
     */
    public static function renderJSON($params, $addHead = true, $terminate = false) {
        if($addHead) {
            header('Content-type: application/json');
        }
        echo json_encode($params);
        if($terminate) {
            exit();
        }
    }

    /**
     * 
     * @param string $text
     * @return string
     */
    public static function encode($text) {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    /**
     * 
     * @return array
     */
    public static function getHelperFunstions() {
        $app = \app\App::current();
        $config = $app->get('\app\AppConfig');
                
        return [

            'priceFormat' => function($price) {
                return number_format($price, 0, ',', ' ') . ' руб';
            },
            'dateFormat' => function($dateStr, $format = null) {
                if (is_null($format)) {
                    $format = 'd.m.Y H:i';
                }
                return date($format, strtotime($dateStr));
            },
                     
            'firstChar' => function($str) {
                return function_exists('mb_substr') ? mb_substr($str, 0, 1) : substr($str, 0, 1);
            },
                    
            'isTestEnv'    => function() use(&$config){                
                return $config->getValue('isLocal', false); 
            },         
        ];
    }

}
