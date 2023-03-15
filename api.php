<?php

require_once __DIR__ . '/bootstrap.php';

//register_shutdown_function(function (){
   // var_dump(error_get_last());
   // die;
//});

use app\controller\MainController;
$main = new MainController();

$main->getTree();