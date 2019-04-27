<?php

if (!session_id()) session_start();
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

define('ROOTDIR', "http" . ($_SERVER['SERVER_PORT'] == "443" || $_SERVER['REQUEST_SCHEME'] == "https" ? "s" : "") . "://" . str_replace('//', '/', $_SERVER['HTTP_HOST'] . "/"));
define('ROOT_FOLDER', dirname(__FILE__) . "/");
define('ROOT_LIB_FOLDER', dirname(__FILE__) . "/core/lib/");

ini_set('display_errors', 1);