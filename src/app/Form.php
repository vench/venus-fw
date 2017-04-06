<?php



namespace app;

use app\Request;

/**
 * Description of Form
 *
 * @author vench
 */
class Form {

    /**
     *
     * @var Object 
     */
    public $model;
    
    /**
     *
     * @var array 
     */
    private $errors = [];
    
 
    
    public function __construct( $model) {
        $this->model = $model;
    }
    
    /**
     * 
     * @return string
     */
    public function getModelName() {
        $name = get_class($this->model);
        return substr($name, strrpos($name, '\\')+1);
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function getName($name) {
        return $this->getModelName() . '['.$name.']';
    }
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function getValue($name) {
        $method = 'get' . ucfirst($name);
        return method_exists($this->model, $method) ? 
                call_user_func_array([$this->model, $method], []) : null;
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setValue($name, $value) {
        $method = 'set' . ucfirst($name);
        if(method_exists($this->model, $method)) {
            call_user_func_array([$this->model, $method], [$value]);
        }       
    }

    
    /**
     * 
     * @param  $request
     * @return boolean
     */
    public function bind( Request $request) { 
        $data = $request->post($this->getModelName());
        if(is_array($data)) {
            foreach($data as $key => $value) {
                $this->setValue($key, $value);
            } 
        }
    }
    
    /**
     * 
     * @return boolean
     * @todo Сделать проверку 
     */
    public function validate() { 
        
        return sizeof($this->errors) == 0;
    }
    
    
    /**
     * 
     * @param string $name
     * @return array
     */
    public function getErrors($name) {
        return isset($this->errors[$name]) ? ($this->errors[$name]) : '';
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    public function hasErrors($name) {
        return isset($this->errors[$name]);
    }
    
     
     
}
