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
     * 
     */
    public function actionTest() {
        var_dump(__METHOD__); 
    }
    
    
 
    
    /**
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONQuery 
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["POST"]}
     */
    public function actionCalk($input = null) {
        
 
       
        
        return [            
            'input' => $input,
            'r'     => mt_rand(0, 255),
        ];
    }
    
    
     
}
