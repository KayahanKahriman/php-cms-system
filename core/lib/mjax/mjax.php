<?php
/**
 * Created by PhpStorm.
 * User: muhittingulap
 * Date: 19.12.2018
 * Time: 16:02
 */


class mjax
{
    public $data = array();

    public function mjx($func, $params = array())
    {
        $this->script("mjx('" . $func . "'" . (count($params) > 0 ? ',' . json_encode($params) : '') . ")");
    }

    public function script($data)
    {
        $this->data["script"][] = $data;
    }

    public function alert($data)
    {
        $this->data["alert"][] = (is_array($data) ? json_encode($data) : $data);
    }

    public function confirm($data)
    {
        $this->data["confirm"][] = array("mess" => $data);
    }

    public function assign($selector, $action, $data)
    {
        $this->data["assign"][] = array("selector" => $selector, "action" => $action, "data" => str_replace("`", '', $data));
    }

    public function redirect($link)
    {
        $this->data["redirect"][] = $link;
    }

    static function dataToarray($REQUEST)
    {
        if (count($REQUEST) > 0) {
            foreach ($REQUEST as $k => $v) {
                if (strpos($v, '=>')) {
                    $a = explode(',', $v);
                    if (count($a) > 0) {
                        $REQUEST[$k] = array();
                        foreach ($a as $b => $c) {
                            $a = explode('=>', $c);
                            $REQUEST[$k][$a[0]] = $a[1];
                        }
                    }
                }
            }
        }
        return $REQUEST;
    }

    static function runMjax()
    {
        $_REQUEST = self::dataToarray($_REQUEST);
        $mjxData = $_REQUEST;
        unset($mjxData[mjxfnc]);
        print json_encode(call_user_func_array($_REQUEST[mjxfnc], array($mjxData)));
        exit;
    }

}