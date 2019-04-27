<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 27.04.2019
 * Time: 10:08
 */

namespace MG\http;

class router
{
    public function __construct()
    {
        $this->route();
    }

    public function route()
    {
        print '<pre>';
        print_r($_SERVER);
        print '</pre>';
        exit;
    }

}