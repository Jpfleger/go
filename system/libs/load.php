<?php
class load{
    /**
     * LOAD THE A VIEW
     * @param string $view  PATH TO THE VIEW
     * @param array [$data = false] VARIABLES TO BE EXTRACTED
     * @param array [$include_go = false] INCLUDE THE GO BASE FILE
     */
    public function view($view,$data = false,$include_go = false){
        
        /***
        * EXTRACT DATA IF THERE
        ****/
        if( $data ) extract($data);
        if( $include_go ) $go = go::get_go();
        
        /***
        * LOAD THE VIEW
        ****/ 
        require_once(BASEPATH.'app/view/'.$view.'.php');
    }
    
    public function template($view){
        
        /***
        * LOAD THE VIEW
        ****/ 
        return file_get_contents(BASEPATH.'app/view/'.$view.'.php');
    }
    
    /**
     * LOAD THE CSS FILE
     * @param boolean [$file = false] NAME OF FILE, IF FALSE LOAD ALL
     */
    public function css($file = false, $query=false){
        
        /*****
        * IF FILE IS PROVIDED, LOAD THAT FILE
        *****/ 
        
        if($file){
            echo '<link rel="stylesheet" href="'.RELATIVEPATH.'css/'.$file.'.css" >';
            return;
        }

    }
    /**
     * LOAD THE JS FILE
     * @param boolean [$file = false] NAME OF FILE, IF FALSE LOAD ALL
     */
    public function js($file = false){
        
        /*****
        * IF FILE IS PROVIDED, LOAD THAT FILE
        *****/ 
        if($file){
            echo '<script src="'.RELATIVEPATH.'js/'.$file.'.js" ></script>';
            return;
        }

    }
    
    /**
     * LOAD GOOGLE BOOTSTAP
     */
    public function bootstrap(){
        echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">';
    }
    
    /**
     * LOAD JQUERY
     */
    public function jquery(){
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>';
    }
}