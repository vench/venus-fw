<?php

namespace vsapp\controller;

use vsapp\ApplyAppableInterface;
use vsapp\AppContextInterface;
use vsapp\util\View;

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
