<?php

require_once __DIR__ . '/vendor/autoload.php';

/**

преобразование массива в одноименные переменные
@param $src
@param array $required_params массив c названием обязательных полей
@param array $optional_params массив с названием опциональных полей
@return array
 */
function checkAndPrepareParams($src, $required_params = [], $optional_params = [])
{
    $vars = [];
    foreach ($required_params as $value) {
        if (!array_key_exists($value, $src)) {
            throw new Exception('Неправильные параметры запроса');
        }
        $vars[$value] = $src[$value];
    }
    foreach ($optional_params as $value) {
        if (array_key_exists($value, $src)) {
            $vars[$value] = $src[$value];
        } else {
            $vars[$value] = null;
        }
    }
    return $vars;
}
