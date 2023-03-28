<?php

require_once __DIR__ . '/bootstrap.php';

use app\controller\MainController;
use app\controller\DocumentController;
use app\controller\ObserverController;
use app\controller\ObservationController;

$main = new MainController();

extract (checkAndPrepareParams($_REQUEST, ['act', 'method']));

switch ($act) {
    case 'main':
        mainFunctions($method);
        break;
    case 'document':
        documentFunctions($method);
        break;
    case 'observer':
        observerFunctions($method);
        break;
    case 'observation':
        observationFunctions($method);
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
            extract(checkAndPrepareParams($_REQUEST, ['parentId', 'name']));
            $main->insertRow($name, $parentId);
            break;
        case 'updateRow':
            extract(checkAndPrepareParams($_REQUEST, ['id'], ['name', 'parentId']));
            $main->updateRow($name, $parentId, $id);
            break;
        case 'displayTree':
            $main->displayTree();
            break;
        default:
            throw new Exception('Unknown main function');
    }
}

function documentFunctions($method)
{
    $document = new DocumentController();
    switch ($method) {
        case 'csvCreate':
            $document->csvCreate();
            break;
        case 'csvRead':
            $document->csvRead();
            break;
        case 'xlsxCreate':
            $document->xlsxCreate();
            break;
        case 'pdfCreate':
            $document->pdfCreate();
            break;
        case 'wordCreate':
            $document->wordCreate();
            break;
        case 'pdfCreateObservation':
            $document->pdfCreateObservation();
            break;
        case 'wordCreateObservation':
            $document->wordCreateObservation();
            break;
        default:
            throw new Exception('Unknown document function');
    }
}

function observerFunctions($method)
{
    $observer = new ObserverController();
    switch ($method) {
        case 'getAllObservers':
            $observer->getAllObservers();
            break;
        case 'getObserver':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $observer->getObserver($id);
            break;
        case 'deleteObserver':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $observer->deleteObserver($id);
            break;
        case 'insertObserver':
            extract(checkAndPrepareParams($_REQUEST, ['name']));
            $observer->insertObserver($name);
            break;
        case 'updateObserver':
            extract(checkAndPrepareParams($_REQUEST, ['id', 'name']));
            $observer->updateObserver($name, $id);
            break;
        default:
            throw new Exception('Unknown observer function');
    }
}

function observationFunctions($method)
{
    $observation = new ObservationController();
    switch ($method) {
        case 'getAllObservations':
            $observation->getAllObservations();
            break;
        case 'getObservationById':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $observation->getObservationById($id);
            break;
        case 'getObservationByTreeId':
            extract(checkAndPrepareParams($_REQUEST, ['treeId']));
            $observation->getObservationByTreeId($treeId);
            break;
        case 'getObservationByObserverId':
            extract(checkAndPrepareParams($_REQUEST, ['observerId']));
            $observation->getObservationByObserverId($observerId);
            break;
        case 'insertObservation':
            extract(checkAndPrepareParams($_REQUEST, ['treeId', 'observerId']));
            $observation->insertObservation($treeId, $observerId);
            break;
        case 'deleteObservation':
            extract(checkAndPrepareParams($_REQUEST, ['id']));
            $observation->deleteObservation($id);
            break;
        case 'updateObservation':
            extract(checkAndPrepareParams($_REQUEST, ['id'], ['treeId', 'observerId']));
            $observation->updateObservation($treeId, $observerId, $id);
            break;
        default:
            throw new Exception('Unknown observer function');
    }
}