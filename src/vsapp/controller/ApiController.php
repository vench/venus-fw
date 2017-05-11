<?php
 

namespace vsapp\controller;

/**
 * Description of ApiController
 *
 * @author vench
 */
class ApiController implements  \vsapp\proxy\AnnotationInterface  {
    
   
    
    /**
     * 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     */
    public function actionTest() {
        return "test";
    }
    
    
    /**
     * 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     */
    public function actionAuth($name = null) {
        
       \vsapp\App::current()->get('webClient')->setIdentifier($name); 
        
       return [
           'name'       => $name,
           'success'    => 'OK',
       ];
    }
    
 
    
    /**
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONQuery 
     * @proxy_exec \vsapp\proxy\filters\controller\Access
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["POST"]}
     */
    public function actionCalk($input = null) {  
        return [            
            'input' => $input,
            'output'     => mt_rand(0, 255),
        ];
    }
    
    /**
     * Factorialis 
     * try /api/fac?n=4
     * 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["GET"]}
     * @proxy_exec \vsapp\proxy\filters\arg\Filter {"rules": [[["0"], ["int"]]]}
     * @proxy_exec \vsapp\proxy\filters\arg\Validator {"rules": [[["0"], ["int"]]]}
     * 
     */
    public function actionFac($n) { 
        
        $f  = function($v) use(&$f){
            if($v <= 0) {
                return 1;
            } 
            return $v -- * $f(  $v  );
        };
        
        return ['input' => $n, 'output' => $f($n)];
    }
     
}
