<?php


?>

<h1>Hello world!</h1>


<?php

$app = \vsapp\App::current();

$app->get('log')->addMessage("Start");

$m = $app->get('\test_\Model');
$m->printName();