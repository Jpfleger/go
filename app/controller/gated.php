<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class gated extends controller{
    
    public function index(){
        echo 'this is gated, if you are here you have a vali session';
    }
    
}