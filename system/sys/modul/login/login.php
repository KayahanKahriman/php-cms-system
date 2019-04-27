<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 27.04.2019
 * Time: 11:30
 */

namespace MG\Modul;

class login
{
    public function __construct()
    {
        $this->config()
            ->view();
    }

    public function config()
    {
        $this->m[config]=array(
            "header" => 0,
            "footer" => 0,
        );
        return $this;
    }

    public function view()
    {
        $this->m["tpl"] = 'login.tpl';

        return $this;
    }


    public function create()
    {
        $this->c_sql();
    }

    public function c_sql()
    {
        $this->m["sql"] = "CREATE TABLE `users` (
                                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                                `username` VARCHAR(50) NULL DEFAULT NULL,
                                `password` VARCHAR(50) NULL DEFAULT NULL,
                                `adsoyad` INT(11) NULL DEFAULT NULL,
                                `email` VARCHAR(100) NULL DEFAULT NULL,
                                `tel` VARCHAR(50) NULL DEFAULT NULL,
                                `tarih` INT(11) NOT NULL DEFAULT '0',
                                PRIMARY KEY (`ID`),
                                INDEX `tarih` (`tarih`),
                                INDEX `username` (`username`),
                                INDEX `password` (`password`)
                            )
                            COLLATE='utf8_general_ci'
                            ENGINE=InnoDB";
        return $this;
    }
}