<?php
class header{
    
    /**
     * LOCATION
     * @param string $string PATH TO BE RELOADED
     */
    public function location($string){
        /****
        *RELOAD A LOCATION
        ****/
        header('Location: '.$string);
    }
    
    
    
    
}