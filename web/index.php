<?php

require_once dirname(__FILE__) . '/../src/vsapp/AutoLoad.php'; 


\vsapp\AutoLoad::init();

class R implements vsapp\IResource {
    public function getPath() {
        echo __FILE__;
        return 'r';
    }

}


$app = \vsapp\App::current();
$app->setClassNameAliases('resource', 'R');

$app->run();

