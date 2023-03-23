<?php

namespace app\service;

class BaseService
{
    protected function mb_ucfirst(string $string): string
    {
        if (!empty($string)) {
            return mb_strtoupper(mb_substr($string,0,1)) . mb_substr($string, 1);
        } else {
            return $string;
        }
    }

    protected function mb_ucfirst_arr(array $array)
    {
        $array['Name'] = $this->mb_ucfirst($array['Name']);
        return $array;
    }
}