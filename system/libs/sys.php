<?php
class sys {
    public function __construct(){
        /****
        *REFERNCE CONTOLLERS
        ****/
        $controllers = scandir(BASEPATH.'/app/controller/');
        foreach($controllers as $k => $v){
            //CHECK TO MAKE SURE FILE IS A PHP
            if(strpos($v,'.php') !== false){
                $controller = preg_replace('/\\.[^.\\s]{3,4}$/', '', $v);
                //ADD LIBRARIES TO CONFIG
                if($controller != 'home' && $controller != 'setup') {
                    $this->controllers[] = strtoupper( $controller );
                }
            }
        }
    }
}