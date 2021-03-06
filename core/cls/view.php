<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 27.04.2019
 * Time: 13:00
 */

namespace MG\Temp;


class view
{
    public function __construct($view = array())
    {
        $this->viewData = $view;
        $this->view();
    }

    public function view()
    {

        $this->engine()
            ->html();
    }

    public function html()
    {

        $this->seo()
            ->header();

        $this->html .= $this->tm->getHtml(MODULTPLDIR . $this->viewData["MODUL"]["tpl"]);
        $this->footer();

        print $this->html;
        exit;
    }

    public function seo()
    {
        $this->seo = array(
            "HEADER" => array(
                "Lang" => '',
                "ThemeColor" => '',
                "BaseUrl" => ROOTDIR . BASEDIR,
                "Title" => '',
                "Desc" => '',
                "Keyw" => '',
                "Copy" => '',
                "Autr" => '',
                "ShortIcon" => '',
                "SiteMap" => '',
                "GoogleVerification" => '',
                "GoogleAnalitics" => '',
                "GoogleBackregister" => '',
                "Header" => '',
                "GoogleRemarketing" => '',
                "GoogleBackorder" => '',
                "Footer" => '',
            ),
        );
        return $this;
    }

    public function header()
    {

        $this->html = $this->tm->getHtml(TEMPTPL . 'core/tpl/head.tpl',$this->seo);
        $this->html .= $this->library()->jsCss('CSS');
        if ($this->viewData["MODUL"]["config"]["header"]) $this->html .= $this->tm->getHtml(TEMPTPL . 'inc/header.tpl');
        return $this;
    }

    public function footer()
    {
        if ($this->viewData["MODUL"]["config"]["header"]) $this->html .= $this->tm->getHtml(TEMPTPL . 'inc/footer.tpl');
        $this->html .= $this->library()->jsCss('JS');
        $this->html .= $this->tm->getHtml(TEMPTPL . 'core/tpl/foot.tpl',$this->seo);
        return $this;
    }

    public function engine()
    {
        if (file_exists(TEMPDIR . 'conf.php')) {
            $conf = array();
            require_once(TEMPDIR . 'conf.php');
            $this->tempConf = @$conf;
            $this->modulCreate();
        } else {
            print 'Template Conf Dosyası Bulunamadı !';
            exit;
        }
        return $this;
    }

    public function modulCreate()
    {
        if ($this->tempConf["temp"]["engine"] == "php") {
            require_once(TEMPDIR . 'tpl/modul/' . $this->viewData["MODUL"]["tpl"]);
            exit;
        } else {
            $this->tm = new \MG\Temp\engine($this->tempConf["temp"]["engine"], TEMPDIR . $this->tempConf["temp"]["compile"]);
        }
        return $this;
    }


    public function library()
    {

        if (file_exists(TEMPDIR . 'conf.php')) {
            require_once(TEMPDIR . 'conf.php');
        }

        if (count($this->tempConf[lib][js][header]) > 0) foreach ($this->tempConf[lib][js][header] as $k => $v) $this->library[JS][HEADER][] = $v;
        if (count($this->tempConf[lib][js][footer]) > 0) foreach ($this->tempConf[lib][js][footer] as $k => $v) $this->library[JS][FOOTER][] = $v;
        if (count($this->tempConf[lib][css][footer]) > 0) foreach ($this->tempConf[lib][css][footer] as $k => $v) $this->library[CSS][FOOTER][] = $v;

        if (count($this->library) > 0) {
            foreach ($this->library as $tip => $veri) {
                $tip = strtolower($tip);
                foreach ($veri as $key => $val) {
                    foreach ($val as $k => $v) {
                        $v = (substr($v, -strlen($tip)) == $tip ? trim($v) : trim($v) . '.' . $tip);
                        $path = "";
                        if (substr($v, 0, 8) != 'https://' && substr($v, 0, 7) != 'http://' && substr($v, 0, 2) != '//') {
                            if (substr($v, 0, 4) == 'lib/') {
                                if (file_exists(ROOT_LIB_FOLDER . ltrim($v, 'lib/'))) {
                                    $path = ROOTDIR . 'core/' . $v;
                                }
                            } else {
                                if (file_exists(TEMPDIR . 'library/' . $tip . '/' . $v)) {
                                    $path = ROOTDIR . BASEDIR . $tip . '/' . $v;
                                } else if (file_exists(ROOT_FOLDER . 'library/' . $tip . '/' . $v)) {
                                    $path = ROOTDIR . BASEDIR . $tip . '/' . $v;
                                }
                            }
                        } else {
                            $path = $v;
                        }

                        if (!$this->library[JSCSS][strtoupper($tip)][$v]) {
                            if ($path) {
                                if ($tip == "css") {
                                    $lp = '<link async href="' . $path . '?v=3" rel="stylesheet" type="text/css" />' . "\n";
                                } else {
                                    $lp = '<script type="text/javascript" src="' . $path . '?v=3"></script>' . "\n";
                                }
                            } else {
                                $lp = '<!-- ' . $v . ' Bu [' . $tip . '] Dosyası Hiç Bir Yerde Bulunamadı -->';
                            }
                            $this->library[JSCSS][strtoupper($tip)][$v] = $lp;
                        }
                    }
                }
            }
        }
        return $this;
    }

    private function jsCss($tip = 'CSS')
    {
        $return = "";
        if (count($this->library[JSCSS][$tip]) > 0) {
            foreach ($this->library[JSCSS][$tip] as $k => $v) {
                $return .= $v;
            }
        }
        return $return;
    }


}