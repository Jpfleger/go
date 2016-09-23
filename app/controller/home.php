<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends controller{
    
    /**
     * INDEX IS THE DEFAULT METHOD FOR EACH CONTROLLER
     * CREATE A NEW FUNCTION AND CALL IT INDEX.PHP/CONTROLLER/METHOD/VAR1/VAR2 
     */
    public function index(){
        
        date_default_timezone_set('America/New_York');
        $res = file_get_contents('https://api.seatgeek.com/2/events?q=new+york+jets');
        $res = json_decode($res);
        echo '<pre>';
        
        foreach($res->events as $k){
            $events['name'] = $k->title;
            $events['date'] = strtotime($k->datetime_local);
            $e[] = $events;
        }
        
        var_dump($e);
        $c = new test;

        /*****
        * LOAD THE A VIEW
        * LOCATION OF VIEW, DATA ARRAY, [INCLUDE GO]
        *****/
        $this->go->load->view('go/welcome',array(),true);
    }

}