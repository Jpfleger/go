<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends controller{
    
    public function index(){
                
    }
    
    public function method($v1,$v2,$v3){
        echo 'works';
        echo '<br>'.$v1;
        echo '<br>'.$v2;
        echo '<br>'.$v3;
    }
    
}