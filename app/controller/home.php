<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends controller{
    
    public function index(){
        //$this->go->session->set(array('name'=>'johnathon'));
        $this->go->session->destroy();
       if( $this->go->session->check() ){
           var_dump($this->go->session->data());
       }else{
           echo 'not set';
       }
        $this->go->load->view('go/welcome',false,true);
    }

}