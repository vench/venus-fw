<?php

require_once dirname(__FILE__) . '/../src/vsapp/AutoLoad.php'; 


\vsapp\AutoLoad::init();

class R implements vsapp\IResource {
    public function getPath() {
         
        return dirname(__FILE__) . '/../src/resource';
    }

}


$app = \vsapp\App::current();
$app->setClassNameAliases('resource', 'R');

$app->run();

$app->get('log')->addMessage("Start");
