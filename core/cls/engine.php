<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 17.04.2019
 * Time: 20:06
 */

namespace MG\Temp;

class engine
{
    public function __construct($engine = 'smarty', $compile = '')
    {
        $this->compile = $compile;
        $this->tm = $engine;
        $this->{$engine}();
    }

    private function smarty()
    {
        require_once(ROOT_LIB_FOLDER . 'smarty/Smarty.class.php');
        $this->engine = new \Smarty;
        $this->engine->setCompileDir(ROOT_FOLDER . $this->compile);

        return $this;
    }

    public function getHtml($tpl, $a = array())
    {
        return $this->{$this->tm . 'GetHtml'}($tpl, $a);
    }

    private function smartyGetHtml($tpl, $a = array())
    {
        $rtpl = "";
        if ($a) $this->engine->assign($a);
        if (file_exists($tpl)) {
            $rtpl = $this->engine->fetch($tpl);
        }
        return $rtpl;
    }

}