<?php

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
        //CHECK FOR INSTANCE
        if(!isset(static::$go)){
            //RETURN NEW INSTANCE
            static::$go = new go();
        }
        //RETURN ALREADY SET INSTANCE
        return static::$go;
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
        //LOAD THE LIBRARIES
        foreach($c->libs as $k => $v){
            //SANITIZE NAME
            $lib_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $v);
            //INSTANTIATE THE LIBRARY AND ADD TO THE SUPER-OBJECT
            $this->{$lib_name} = new $lib_name();
        }
    }
}

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