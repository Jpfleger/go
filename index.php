<?php
/***
DEFINE BASE PATH
***/
define('BASEPATH','./');

/*************
*WELCOME TO GO
*ANOTHER FRAMWORK FROM JOHN PFLEGER
*YOU DON'T NEED TO MAKE ANY CHANGES TO THIS FILE
**************/

/*************
*LOAD BOOTSTRAP
*************/
require_once(BASEPATH.'/system/core/bootstrap.php');

/************
* BUILD THE ROUTE
***********/
$r = new router;

/**********
 LOAD THE ROUTE
 **********/
$r->load();

