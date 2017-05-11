<?php
 
namespace vsapp\proxy\filters\arg;

/**
 * Description of Filter
 *
 * @author vench
 */
class Filter implements \vsapp\proxy\AnnotationFilterInterface , \vsapp\ApplyAppableInterface {
    
    




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
            list($argumentNames, $filterList) = $rule;
            
            foreach($argumentNames as $argName) {
                if(isset($arguments[$argName])) {
                    $arguments[$argName] = $this->prepareFilters($arguments[$argName], $filterList);
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
     * @param type $filterList
     * @return type
     */
    public function prepareFilters($value, $filterList) {
        foreach ($filterList as $filterName) {
            $filter = $this->getFilter($filterName);
            if($filter) {
                $value = call_user_func_array($filter, [$value]);
            }
        }
        
        return $value;
    }
    
    
    /**
     * 
     * @param string $name classname or name from plainFilters
     * @return \Closure
     */
    public function getFilter($name) {
        $list = self::plainFilters();
        if(isset($list[$name])) {
            return $list[$name];
        }
        
        return $this->app->get($name);
    }
    
    /**
     * 
     * @return array
     */
    public static function plainFilters() {
        return [
            'trim'  => function($v){ return trim($v); },
            'float' => function($v){ return floatval($v); },
            'int'   => function($v){ return intval($v); },
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
