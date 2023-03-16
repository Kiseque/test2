<?php

require_once __DIR__ . '/bootstrap.php';

use app\controller\MainController;
$main = new MainController();

$main->updateRow($_GET['name'], $_GET['parent_id'], $_GET['id']);
