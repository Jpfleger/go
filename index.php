<?php
/*************
*WELCOME TO GO
*ANOTHER FRAMWORK FROM JOHN PFLEGER
*YOU DON'T NEED TO MAKE ANY CHANGES TO THIS FILE
**************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//phpinfo();

/***
* DEFINE BASE PATH
***/
define('BASEPATH','./');

/***
* DEFINE RELATIVE PATH FOR LOADING TO PUBLIC VIEW
***/
define('RELATIVEPATH','/');

/*************
*LOAD BOOTSTRAP
*************/
require_once(BASEPATH.'system/core/bootstrap.php');

/************
* BUILD THE ROUTE
***********/
$r = new router;

/**********
 LOAD THE ROUTE
 **********/
$r->load();

/**********
 UNLOAD THE ROUTE 
 DEBUG = FALSE
 **********/
$r->unload(false);