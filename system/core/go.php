<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
/*************
*MAIN SUPER OBJECT
*THIS WILL GET INSTANTIATED ONCE AND ALL LIBRARIES WILL BE LOADED INTO THE OBJECT 
**************/

class go{
    
    /********
    *INSTANCE HLDER 
    ********/
    private static $go;
    
    /********
    *INSTANCE CHECKER
    *RETURNS THE SINGLE INSTSTANCE THAT WAS LOADED
    *********/
    public static function get_go(){
        //RETURN THE SINGLETON
        return (!isset(static::$go)) ? new go() : static::$go;
        
    }
    
    //USED TO CONSTRUCT THE FIRST INSTANCE
    private function __construct(){
        
        //LOAD THE LIBRARIES
        $this->load_libraries();

    }
    
    
    /********
    *LIBRARY LOADER
    *LOADS ALL THE LIBRARIES IN THE LIBRARY DIRECTORY
    *********/
    private function load_libraries(){
        $c = config::get_config();
        
        /*****
        * LOOP AND LOAD THE LIBRARY FILES
        *****/
        foreach($c->libs as $k => $v){
            
            /*****
            * SANATIZE THE NAME FROM THE FILE
            *****/
            $lib_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $v);
            
            /*****
            * INSTANTIATE LIBRARAY INTO THE SUPER OBJECT
            *****/
            $this->{$lib_name} = new $lib_name();
        }
    }
    
    
}



/**************
*CONFIG CLASS
*HOLDS CONFIG SETTINGS 
**************/

class config{

    private static $con;
    /**
     * SINGLETON FUNCTION
     * @return regExp RETURNS INSTANCE
     */
    public static function get_config($C = false){
        //CHECK FOR INSTANCE
        if(!isset(static::$con)){
            //RETURN NEW INSTANCE
            static::$con = new config($C);
        }
        //RETURN ALREADY SET INSTANCE
        return static::$con;
    }
    
    /**
     * CONSTRUCTOR
     * @private
     */
    private function __construct($c){
        //LOOP AND SET THE SETTINGS
        foreach($c as $k => $v){
            $this->{$k} = $v;
        }
        
    }
}

/**************
*DATABASE CLASS
*HOLDS DATABASE SETTINGS 
**************/

class db{

    private static $db;
    /**
     * SINGLETON FUNCTION
     * @return regExp RETURNS INSTANCE
     */
    public static function get_db(){
        //CHECK FOR INSTANCE
        if(!isset(static::$db)){
            //RETURN NEW INSTANCE
            static::$db = new db();
        }
        //RETURN ALREADY SET INSTANCE
        return static::$db;
    }
    
    /**
     * CONSTRUCTOR
     * @private
     */
    private function __construct(){
        //LOOP AND SET THE SETTINGS
        $this->connect();
    }
    
     /**
     * CONNECT THE THE DATABASE
     */
    private function connect(){
        //CREATE CONNECTION VARIABLE
        $c = config::get_config();
        $this->con = mysqli_connect($c->HOSTNAME, $c->USERNAME, $c->PASSWORD,$c->DATABASE);
        
        //CONNECTION ISSUE?
        if(!$this->con){
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        } 
    }
}