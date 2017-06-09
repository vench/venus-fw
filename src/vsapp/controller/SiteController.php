<?php

namespace vsapp\controller;

use vsapp\ApplyAppableInterface;
use vsapp\AppContextInterface;
use vsapp\util\View;
use vsapp\proxy\AnnotationInterface;
 
/**
 * Description of HomeController
 *
 * @author vench
 * @proxy_exec \test_\NameClassEcho
 */
class SiteController implements ApplyAppableInterface, AnnotationInterface {
    
    


    /**
     *  
     */
    public function actionIndex() {  
        View::renderPhp('site/index', [ ]); 
    }
 
    /**
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult {"autoExit":true}     
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["POST", "GET"]}
     * @proxy_exec \vsapp\proxy\filters\controller\Access
     */
    public function actionTest() {
        return [1, 2, 3, 4, 5];
    }
 

    /**
     * 
     * @param app\AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) { 
        //TODO
    }

}
