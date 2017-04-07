
<?php

require_once '../../autoload.php';


\vsapp\AutoLoad::addDirectory(
        dirname(__FILE__) . '/../');

$app = \vsapp\App::current();
$app->setClassNameAliases('resource', '\app\Resource');

$app->run();