<?php
/*************
*THIS IS THE BOOTSTRAP FILE
*YOU DON'T NEED TO MAKE ANY CHANGES TO THIS FILE
**************/

/*************
*LOAD CONFIG FILE
**************/
require_once('./system/core/go.php');

//CLEAN INSTALL
if(!file_exists('./system/core/config.php')){
    require_once('./system/core/cleaninstall.php');
    die();
}
/*************
*LOAD CONFIG FILE
**************/
require_once('./system/core/config.php');

/*************
*LOAD MVC FILE
**************/
require_once('./system/core/mvc.php');

/*************
*LOAD LIB FILES
**************/
$libs = scandir('./system/libs/');
//UNSET DIR
unset($libs[0],$libs[1]);
$c = config::get_config();
$c->libs =  $libs;
//LOOP AND LOAD
foreach($libs as $k => $v){
    //REQUIRE LIBRARIES TO BE LOADED
    require_once('./system/libs/'.$v);
}
//REQUIRE GO
require_once('./system/core/go.php');

/*************
*INITIALIZE SUPER OBJECT USING THE SINGLETON PATTERN
*PASS IN THE CONFIG FILE SO THAT IT'S ACCESSABLE THROUGH ALL LIBRARIES
*************/
go::get_go();


