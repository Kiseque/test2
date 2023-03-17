<?php

require_once __DIR__ . '/bootstrap.php';

use app\controller\MainController;
use app\controller\DocumentController;
$main = new MainController();

extract (checkAndPrepareParams($_REQUEST,
[
    'act', 'method'
]));

if ($act === 'main' && $method == 'getTree') {
    $main->getTree();
}

switch ($act) {
    case 'main':
        mainFunctions($method);
        break;
    case 'document':
        documentFunctions($method);
        break;
    default:
        throw new Exception('Unknown act');
}

function mainFunctions($method)
{
    $main = new MainController();
    switch ($method) {
        case 'getTree':
            $main->getTree();
            break;
        case 'getRow':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $main->getRow($id);
            break;
        case 'deleteRow':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $main->deleteRow($id);
            break;
        case 'insertRow':
            extract(checkAndPrepareParams($_REQUEST, ['parent_id', 'name']));
            $main->insertRow($name, $parent_id);
            break;
        case 'updateRow':
            extract(checkAndPrepareParams($_REQUEST, ['id'], ['name', 'parent_id']));
            $main->updateRow($name, $parent_id, $id);
            break;
        default:
            throw new Exception('Unknown main function');
    }
}

function documentFunctions($method)
{
    $main = new DocumentController();
    switch ($method) {
        case 'csvCreate':
            $main->csvCreate();
            break;
        default:
            throw new Exception('Unknown document function');
    }
}