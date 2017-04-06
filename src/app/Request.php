<?php


namespace app;

use app\ApplyAppableInterface;
use app\AppContextInterface;

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
     * 
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return filter_input(INPUT_GET, $name);
    }
    
    /**
     * 
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
        return !is_null($action) ? $action : $this->getDefaultAction(); 
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
        $appConfig = $app->get('app\AppConfig'); 
        $this->defaultPage = $appConfig->getValue('defaultPage');
    }

}
