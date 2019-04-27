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

        $this->parse_url()
            ->protokol();

        $this->SYS = array(
            "CONFIG" => array(
                "HOST" => $_SERVER["HTTP_HOST"],
                "ROOTLINK" => $this->PROTOKOL . '://' . $_SERVER["HTTP_HOST"] . '/',
                "MODELLINK" => $this->PROTOKOL . '://' . $_SERVER["HTTP_HOST"] . '/' . (trim($this->p[0]) ? trim($this->p[0]) . '/' : ''),
            ),
            "LOCATION" => array(
                "MODEL" => (trim($this->p[0]) ? trim($this->p[0]) : 'index'),
                "PAGE" => (trim($this->p[1]) ? trim($this->p[1]) : 'index'),
                "METHOD" => (trim($this->p[2]) ? trim($this->p[2]) : 'list'),
                "PARAMETRE" => $this->parametre(),
            ),
        );

        $this->routeDefine()
            ->routeCreate();
    }

    public function routeCreate()
    {
        /* modül varmı kontrol ediliyor ve dahil ediliyor */
        if (file_exists(MODULDIR)) {
            require_once(MODULDIR);
            $mclas = '\MG\Modul\\' . PAGE;
            $this->module = new $mclas();

            $this->view = array(
                "ROUTER" => $this->SYS,
                "MODUL" => $this->module->m,
            );

            new \MG\Temp\view($this->view);
        } else {
            print 'Modül Bulunamadı !';
            exit;
        }
    }


    public function routeDefine()
    {
        define(MODEL, $this->SYS["LOCATION"]["MODEL"]);
        define(PAGE, $this->SYS["LOCATION"]["PAGE"]);

        define(TEMP, '/sys/src/temp/t1/');
        define(BASEDIR, MODEL.TEMP . 'library/');

        define(TEMPDIR, ROOT_FOLDER . MODEL . TEMP);
        define(TEMPTPL, ROOT_FOLDER . MODEL . TEMP . 'tpl/');

        define(MODULTPLDIR, TEMPTPL . 'modul/');

        define(MODUL, PAGE . '/' . PAGE . '.php');
        define(MODULDIR, ROOT_FOLDER . MODEL . '/' . 'sys/modul/' . MODUL);




        return $this;
    }


    public function parse_url()
    {
        $this->p = $_SERVER["REQUEST_URI"];

        $this->p = trim($this->p);
        $this->p = ltrim($this->p, "/");
        $this->p = rtrim($this->p, "/");
        $this->p = explode("/", $this->p);

        /* eğer model klasörü yoksa ana index */
        if (!file_exists(ROOT_FOLDER . $this->p[0])) {
            $p = array(0 => '');
            foreach ($this->p as $k => $v) {
                $p[$k + 1] = $v;
            }
            $this->p = $p;
        }

        return $this;
    }

    public function parametre()
    {
        $p = array();
        if (count($this->p) > 3) {
            for ($i = 3; $i < count($this->p); $i++) {
                $p[$i - 3] = $this->p[$i];
            }
        }

        return $p;
    }


    public function protokol()
    {
        if ($_SERVER["REQUEST_SCHEME"] == "") {
            if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
                $this->PROTOKOL = "https";
            } else {
                $this->PROTOKOL = "http";
            }
        } else {
            $this->PROTOKOL = $_SERVER["REQUEST_SCHEME"];
        }
        return $this;
    }


}