<?php

namespace app\service;

class BaseService
{

    protected function mb_ucfirst(string $string): string
    {
        $temp = mb_strtoupper(mb_substr($string,0,1));
        $temp .= mb_substr($string, 1);
        return $temp;
    }
}