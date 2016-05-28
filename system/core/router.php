<?php
/*********
//HANDLES THE NAVIGATION
*********/
class router {
    
    /**
     * BUILD THE DESTINATION
     * @private
     */
    public function __construct(){
        
        //GRAB THE ROUTE OF THE 
        $this->route = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        
        /***********
        * REMOVE THE INDEX FILE AND THE OPENING BLANK VALUE
        * THIS METHOD WILL NOT BE AFFECTED BY HT ACCESS CHANGES
        ***********/
        
        
        
        foreach($this->route as $k => $seg){   
            if($seg == '' || $seg == 'index.php'){
               unset($this->route[$k]);
            };
        }
        
        /*********
        * LOCATE THE CLASS FILE
        * SET THE CLASS FILE IN THIS ROUTER CLASS
        * THIS WILL BE INSTANTIATED IN THE BOOTSTRAP FILE
        * *******/
        
        $this->controller = array_shift($this->route);
        
        
        /********
        * LOCATE AND SET THE METHOD
        * THIS WILL BE CALLED BY THE BOOTSTRAP FILE
        * *******/
        
        $this->method = array_shift($this->route);
        
        
        /********
        * CHECK IF THERE IS A METHOD IN THE ARRAY
        * DEFAULT TO INDEX IF NOT
        * *******/
        
        if(!$this->method){
            $this->method = 'index';
        }
        
        /*******
        * NOW ALL THAT REMAINS IS THE VARS FOR THE METHOD TO BE CALLED
        * TRANSFER THESE TO THE VARS ARRAY
        * *****/
        
        $this->variables = $this->route;
    
    }
    
    /**
     * LOAD THE PATH
     */
    public function load(){
        
        /**
        * LOAD THE CONTROLLER FILE FROM THE 
        * CONTROLLER APP DIRECTORY BUT ONLY IF EXISTS
        * IF NOT, RUN 404 PROCESS
        * ***/
         
        if(file_exists('./app/controller/'.$this->controller.'.php')){
            
            /**
            * CONTROLLER FILE EXISTS IN THE APP DIRECTORY
            * REQURE THE FILE
            * **/ 
            
            require_once('./app/controller/'.$this->controller.'.php');
            
            
            /**
            * INSTANTIATE THE CONTROLLER
            * **/
             
            $route = new $this->controller;
            
            
            /**
            * NOW CHECK IF THE METHOD EXISTS IN THE CONTROLLER CLASS
            * IF NO METHOD EXISTS THEN THROW 404 PROCESS
            * */
             
            if(method_exists($route,$this->method)){
                
                /**
                * METHOD EXISTS, THE PATH HAS BEEN SUCCESSFULY VALIDATED
                * CALL THE METHOD WITH ANY VARS
                * **/
                 
                call_user_func_array([$route,$this->method],$this->variables);

             /**
             * METHOD DOES NOT EXIST
             * 404
             * **/
              
            }else{
                var_dump($this);
                //THROW A 404
                echo '404';
            }
            
        /**
        * CONTROLLER DOES NOT EXIST
        * 404
        * **/
            
        }else{
            var_dump($this);
            //THROW A 404
            echo '404';
        }
    }
    
}
