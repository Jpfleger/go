<?php

class home extends controller{
    
    public function index(){
        $x = new test;
        echo '<pre>';
       // var_dump($x);
    }
    
    public function method($v1,$v2,$v3){
        echo 'works';
        echo '<br>'.$v1;
        echo '<br>'.$v2;
        echo '<br>'.$v3;
    }
    
}