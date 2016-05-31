<?php

/*************
*THIS IS THE BOOTSTRAP FILE
*YOU DON'T NEED TO MAKE ANY CHANGES TO THIS FILE
**************/

/*************
*LOAD GO FILE
**************/
require_once(BASEPATH.'system/core/go.php');

/*************
*CLEAN INSTALL
**************/
if(!file_exists(BASEPATH.'system/core/config.php')){
    require_once(BASEPATH.'system/core/cleaninstall.php');
    die();
}

/*************
*LOAD ROUTER
**************/
require_once(BASEPATH.'system/core/router.php');

/*************
*LOAD MVC FILE
**************/
require_once(BASEPATH.'system/core/mvc.php');

/*************
*LOAD CONFIG FILE
**************/
require_once(BASEPATH.'system/core/config.php');

/*************
*LOAD LIB FILES
**************/
$libs = scandir(BASEPATH.'system/libs/');
$c = config::get_config();

//UNSET DIR
unset($libs[0],$libs[1]);

//ADD LIBRARIES TO CONFIG
$c->libs = $libs;

//LOOP AND LOAD
foreach($libs as $k => $v){
    
    //REQUIRE LIBRARIES TO BE LOADED
    require_once(BASEPATH.'system/libs/'.$v);
    
}

/*************
*LOAD MODELS
**************/
$mod = scandir(BASEPATH.'app/model/');

//UNSET DIR
unset($mod[0],$mod[1]);

//SET MODELS TO CONFIG
$c->mod = $mod;

//LOOP AND LOAD
foreach($mod as $k => $v){
    //REQUIRE LIBRARIES TO BE LOADED
    require_once(BASEPATH.'app/model/'.$v);    
}

/*************
*INITIALIZE SUPER OBJECT USING THE SINGLETON PATTERN
*PASS IN THE CONFIG FILE SO THAT IT'S ACCESSABLE THROUGH ALL LIBRARIES
*************/
go::get_go();




