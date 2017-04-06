<?php

namespace app\controller;

use app\ApplyAppableInterface;
use app\AppContextInterface;
use app\util\View;

/**
 * Description of HomeController
 *
 * @author vench
 */
class SiteController implements ApplyAppableInterface {
    
    


    /**
     * 
     * @param int $p
     */
    public function actionIndex() {  
        View::renderPhp('site/index', [ ]); 
    }
 
 

    /**
     * 
     * @param app\AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) { 
        //TODO
    }

}
