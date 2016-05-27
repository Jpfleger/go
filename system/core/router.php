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
        echo '<pre>';
        var_dump($_SERVER, PHP_URL_PATH);
        die();
        
        /***********
        * REMOVE THE INDEX FILE AND THE OPENING BLANK VALUE
        * THIS METHOD WILL NOT BE AFFECTED BY HT ACCESS CHANGES
        ***********/
        echo '<pre>';
        var_dump($this->route);
        foreach($this->route as $k => $v ){
            if(strtolower($v) == '' || strtolower($v) == 'index.php'){
                 array_shift($this->route);
            }
        }
        echo '<pre>';
        var_dump($this->route);
        /*********
        * LOCATE THE CLASS FILE
        * SET THE CLASS FILE IN THIS ROUTER CLASS
        * THIS WILL BE INSTANTIATED IN THE BOOTSTRAP FILE
        * *******/
        
        $this->controller = array_shift($this->route);
        
        
        
        
        /*******
        * LOAD THE CONTROLLER FILE
        * *****/
        
        //$this->load($this->controller);
        
        /********
        * LOCATE AND SET THE METHOD
        * THIS WILL BE CALLED BY THE BOOTSTRAP FILE
        * *******/
        
        $this->method = array_shift($this->route);
        
        /*******
        * NOW ALL THAT REMAINS IS THE VARS FOR THE METHOD TO BE CALLED
        * TRANSFER THESE TO THE VARS ARRAY
        * *****/
        
        $this->variables = $this->route;
    
    }
    
    /**
     * LOAD THE CONTROLLER FILE
     * @param string $controller NAME OF THE CONTROLLER CLASS
     */
    public function load(){
        if(file_exists('./app/controller/'.$this->controller.'.php')){
            //LOAD THE CONTROLLER FILE
            require_once('./app/controller/'.$this->controller.'.php');
            //INSTANTIATE
            $route = new $this->controller;
            //CHECK IF THE METHO EXISTS
            if(method_exists($route,$this->method)){
                //CALL THE METHOD WITH ANY VARS
                call_user_func_array([$route,$this->method],$this->variables);

            }else{
                var_dump($this);
                //THROW A 404
                echo '404';
            }
        }else{
            var_dump($this);
            //THROW A 404
            echo '404';
        }
    }
    
}
