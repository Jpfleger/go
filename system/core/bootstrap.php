<?php
/*************
*THIS IS THE BOOTSTRAP FILE
*YOU DON'T NEED TO MAKE ANY CHANGES TO THIS FILE
**************/

/*************
*LOAD CONFIG FILE
**************/
require_once('./config.php');

//LOAD 
if(!$config['set']){
    require_once('./system/core/cleaninstall.php');
}