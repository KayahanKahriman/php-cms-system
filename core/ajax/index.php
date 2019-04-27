<?php

require_once(ROOT_LIB_FOLDER . "mjax/mjax.php");

$ajax_folder = array(
    "genel",
);

if (count($ajax_folder) > 0) {
    foreach ($ajax_folder as $k => $v) {
        if ($v) {
            require_once(ROOT_FOLDER . "core/ajax/mjax/" . $v . ".php");
        }
    }
}

if ($_REQUEST[mjxfnc]) mjax::runMjax();