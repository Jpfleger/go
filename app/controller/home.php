<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends controller{
    
    public function index(){
        $x = new test;
        echo '<pre>';
        $res = $this->go->query->sql("SELECT * FROM test");
        var_dump($res);
        die();
       // var_dump($x);
    }
    
    public function method($v1,$v2,$v3){
        echo 'works';
        echo '<br>'.$v1;
        echo '<br>'.$v2;
        echo '<br>'.$v3;
    }
    
}