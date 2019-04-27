<?php
/**
 * Created by PhpStorm.
 * User: webcozumevi
 * Date: 27.04.2019
 * Time: 10:20
 */

/* lib */
require_once(ROOT_FOLDER . 'core/lib/mgdb/mgdb.php');

/* cls */
require_once(ROOT_FOLDER . 'core/cls/engine.php');
require_once(ROOT_FOLDER . 'core/cls/view.php');
require_once(ROOT_FOLDER . 'core/cls/router.php');

/* run */
new \MG\http\router();