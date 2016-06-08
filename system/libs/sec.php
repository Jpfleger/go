<?php
/*******
* SECURITY CLASS
*******/
class sec {
    
    /**
     * HASH A STRING WITH OR WITHOUT SALT
     * @param  string $string         STRING TO BE HASED
     * @param  string [$salt = false] SALT
     * @return string HASHED STRING
     */
    public function hash($string,$salt = false){
        
        /*****
        * GET CONFIG
        ******/
        $c = config::get_config();
        
        /*****
        * ADD SALT
        ******/
        if($salt){
            $string = $string . $salt;
        }
        
        /*****
        * RETURN HASED STRING
        ******/
        return hash($c->HASH_TYPE,$string);
    }
}