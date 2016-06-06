<?php
class load{
    /**
     * LOAD THE A VIEW
     * @param string $view  PATH TO THE VIEW
     * @param array [$data = false] VARIABLES TO BE EXTRACTED
     */
    public function view($view,$data = false){
        /***
        * EXTRACT DATA IF THERE
        ****/
        if( $data ) extract($data);
        
        /***
        * LOAD THE VIEW
        ****/ 
        require_once('./app/view/'.$view.'.php');
    }
    
    public function template($view){
        
        /***
        * LOAD THE VIEW
        ****/ 
        return file_get_contents('./app/view/'.$view.'.php');
    }
}