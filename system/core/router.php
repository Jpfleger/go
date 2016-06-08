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
        
        $this->controller = strtolower( array_shift($this->route) );
        
        /********
        * CHECK IF THERE IS A CONTROLLER IN THE ARRAY
        * DEFAULT TO DEFAULT CONTROLLER IF NOT
        * *******/
        
        if(!$this->controller){
            $c = config::get_config();
            
            $this->controller = $c->DEFAULT_CONTROLLER;
        }
        
        /********
        * LOCATE AND SET THE METHOD
        * THIS WILL BE CALLED BY THE BOOTSTRAP FILE
        * *******/
        
        $this->method = strtolower( array_shift($this->route) );
        
        
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
         
        /****
        * CHECK IF CONTROLLER IS GATED
        ****/
        $c = config::get_config();
        if( $c->GATED ){
            
            /*****
            * APPLICATION IS GATED, CHECK IF CONTROLLER IS GATED
            ******/
            if( !in_array($this->controller, $c->NON_GATED_CONTROLLERS ) ){
                
                /****
                * CONTROLLER IS GATED, REPLACE WITH FALLBACK
                *****/
                $this->controller = strtolower( $c->GATED_FALLBACK_CONTROLLER );
                
                /****
                * REPLACE WITH DEFAULT METHOD 
                *****/
                $this->method = 'index';
                
            }
        }
         
        if(file_exists(BASEPATH.'app/controller/'.$this->controller.'.php')){
            
            /**
            * CONTROLLER FILE EXISTS IN THE APP DIRECTORY
            * REQURE THE FILE
            * **/ 
            
            require_once(BASEPATH.'app/controller/'.$this->controller.'.php');
            
            
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
                require_once(BASEPATH.'app/view/404.php');
            }
            
        /**
        * CONTROLLER DOES NOT EXIST
        * 404
        * **/
            
        }else{
            //THROW A 404
            require_once(BASEPATH.'app/view/404.php');
        }
    }
    
}
