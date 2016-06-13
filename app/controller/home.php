<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends controller{
    
    /**
     * INDEX IS THE DEFAULT METHOD FOR EACH CONTROLLER
     * CREATE A NEW FUNCTION AND CALL IT INDEX.PHP/CONTROLLER/METHOD/VAR1/VAR2 
     */
    public function index(){
        /*****
        * LOAD THE A VIEW
        * LOCATION OF VIEW, DATA ARRAY, [INCLUDE GO]
        *****/
        $this->go->load->view('go/welcome',array(),true);
    }

    

    
}