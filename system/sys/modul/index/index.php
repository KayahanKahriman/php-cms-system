<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 27.04.2019
 * Time: 11:30
 */

namespace MG\Modul;

class index
{
    public function __construct()
    {
        $this->config()
            ->view();
    }

    public function config()
    {
        $this->m[config] = array(
            "header" => 1,
            "footer" => 1,
        );
        return $this;
    }

    public function view()
    {
        $this->m["tpl"] = 'index.tpl';

        return $this;
    }


    public function create()
    {
        $this->c_sql();
    }

    public function c_sql()
    {
        $this->m["sql"] = "";
        return $this;
    }
}