<?php
 

namespace vsapp\proxy\filters\arg;

/**
 * Description of Validator
 *
 * @author vench
 */
class Validator implements \vsapp\proxy\AnnotationFilterInterface , \vsapp\ApplyAppableInterface { 

    /**
     * Configure filter arguments
     * 
     * @var array [ [arguments name], [filters] ]
     */
    public $rules = [];
    
    /**
     *
     * @var \vsapp\AppContextInterface
     */
    private $app;



    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $result
     */
    public function execAfter($object, $name, $result) {
        
    }

    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function execBefore($object, $name, &$arguments) {
        if(empty($this->rules)) {
           return; 
        }
        
        
        foreach($this->rules as $rule) {
            list($argumentNames, $validatorList) = $rule;
            
            foreach($argumentNames as $argName) {
                if(isset($arguments[$argName])) {
                   $this->prepareValidators($arguments[$argName], $validatorList);
                }
            }
        }
    }

    /**
     * 
     * @param type $object
     * @param type $name
     * @param type $exception
     */
    public function execException($object, $name, $exception) {
        
    }
    
    
    /**
     * 
     * @param type $value
     * @param type $validatorList
     * @return type
     */
    public function prepareValidators($value, $validatorList) {
        $valueArray = [$value];
        foreach ($validatorList as $filterName) {
            $validator = $this->getValidator($filterName);
            if($validator) {
                call_user_func_array($validator, $valueArray);
            }
        } 
    }
    
    
    /**
     * 
     * @param string $name classname or name from plainValidators
     * @return \Closure
     */
    public function getValidator($name) {
        $list = self::plainValidators();
        if(isset($list[$name])) {
            return $list[$name];
        }
        
        return $this->app->get($name);
    }
    
    /**
     * 
     * @return array
     */
    public static function plainValidators() {
        return [ 
            'float' => function($v){ 
                if(filter_var($v, FILTER_VALIDATE_FLOAT) === false) {
                    throw new \Exception("Error value type float", 500);
                } 
             },
            'int'   => function($v){
                if(filter_var($v, FILTER_VALIDATE_INT) === false) {
                    throw new \Exception("Error value type int", 500);
                } 
            },
        ];
    }
    
    /**
     * 
     * @param \vsapp\AppContextInterface $app
     */
    public function appInit(\vsapp\AppContextInterface $app) {
        $this->app = $app;
    }

}