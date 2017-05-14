<?php


namespace vsapp;

use vsapp\ApplyAppableInterface;
use vsapp\AppContextInterface;

/**
 * Description of Request
 *
 * @author vench
 */
class Request implements ApplyAppableInterface {
    
    const ACTION_NAME = 'a';
    
    /**
     *
     * @var string
     */
    private $defaultPage;




    /**
     * Get value from GET params
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return filter_input(INPUT_GET, $name);
    }
    
    /**
     * Get value from POST params
     * @param string $name
     * @return mixed
     */
    public function post($name) {
        return filter_input(INPUT_POST, $name, FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
    }

    /**
     * 
     * @return string
     */
    public function getAction() { 
        $action = $this->get(self::ACTION_NAME);
        if(empty($action) && !empty($path = $this->server('PATH_INFO'))) {
            //@todo math 
            $action = $path;
        } 
        return !is_null($action) ? $action : $this->getDefaultAction(); 
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function server($name) {
        return filter_input(INPUT_SERVER, $name);
    }
    
    /**
     * 
     * @return string 
     */
    public function getDefaultAction() {
        return $this->defaultPage;
    }
    
    /**
     * 
     * @param type $action
     * @param array $options
     */
    public function redirect($action, $options = array()) {
        $options[self::ACTION_NAME] = $action; 
        $url = '/index.php?' . http_build_query($options);
        header('location: '.$url.'');
        exit(0);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isPost() {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * 
     * @param \AppContextInterface $app
     * @todo Добавить настройку из контекста приложения
     */
    public function appInit(AppContextInterface $app) {
        /* @var $appConfig \app\AppConfig */
        $appConfig = $app->get('config'); 
        $this->defaultPage = $appConfig->getValue('defaultPage');
    }

}
