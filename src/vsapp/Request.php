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
     *
     * @var array
     */
    private $queryData = null;
    
    /**
     *
     * @var array
     */
    private $routePathActions = [];


    /**
     * Get value from GET params
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        $query = $this->queryAll();
        return isset($query[$name]) ? $query[$name] : null;
    }
    
    /**
     * 
     * @return mixed
     */
    public function queryAll() {
        if(is_null($this->queryData)) {
            $this->queryData = filter_input_array(INPUT_GET) ? : [];
        }
        return $this->queryData;
    }
    
    
    /**
     * 
     * @param array $queryData
     */
    public function addQueryData(array $queryData) {
        if(is_array($queryData)) {
            $this->queryData = array_merge($this->queryAll(),$queryData );
        }
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
            
            $rv = new RequestVisitor($this);
            $rv->path = $path;
            
            $action = $path;
            foreach ($this->routePathActions as $pathAction => $options) {
                if($rv->check($options)) {
                    $action = $pathAction;
                    break;
                } 
            }  
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
        $this->routePathActions = $appConfig->getValue('routePathActions', []);
        
        
    }

}



class RequestVisitor {



    
    /**
     *
     * @var string 
     */
    public $path = null;
    
    /**
     *
     * @var string 
     */
    public $method;


    /**
     *
     * @var Request
     */
    private $request;




    public function __construct(Request $request) {
        $this->request = $request;
        $this->method = strtoupper( filter_input(INPUT_SERVER, 'REQUEST_METHOD') );
    }




    /**
     * 
     * @param array $params
     * @return boolean
     */
    public function check( $params = []) { 
        
        
        $mathQueryParams = null;
        
        foreach ($params as $name => $value) {
            
            
            
            if(isset($this->{$name}) && strcasecmp($this->{$name}, $value) !== 0) { 
                $mathQueryParams = [];
                $value = preg_replace_callback('/<(\S+)\:(.+)>/i', function($math) use(&$mathQueryParams) {                      
                    if(isset($math[1])) {
                        $mathQueryParams[$math[1]] = null;
                    }
                    return '(?<'.$math[1].'>'.$math[2].')'; }, 
                    str_replace(['/'], ['\/'], $value));
                $pattern = "/^{$value}$/i";
                if($name == 'path' && preg_match($pattern, $this->{$name}, $math)) {
                    
                    if(!empty($mathQueryParams)) { 
                        foreach($mathQueryParams as $key => $val) {
                            if(isset($math[$key])) {
                                $mathQueryParams[$key] = $math[$key];
                            }
                        } 
                    }
                    continue;
                }
                
                return false;
            }
        }
        
       
        if(!empty($mathQueryParams)) {
            $this->request->addQueryData($mathQueryParams);
        }
        
        return true;
    }
}