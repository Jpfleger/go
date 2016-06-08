<?php

class mongodb{
    
    private $db;
    /**
     * CONSTURCT IF ENABLED
     */
    public function __construct(){
        
        /*****
        * GET CONFIG
        ****/
        $c = config::get_config();
        
        /*****
        * NOT ENABLED RETURN
        ****/
        if(!$c->MONGODB) return;
    
        /*****
        * CONNECT TO DABASE
        ****/
        $m = new Mongo($c->MONGO_LOCATION);
        
        /*****
        * CONNECT TO DABASE
        ****/
        $this->db = $m->selectDB($C->MONGO_DB_NAME);
        
    }
    
    /**
     * FIND FUNCTION
     * @param  array $data PARAMS TO BE FOUND
     * @return object MONGO RESULT
     */
    public function find($data){
        
        /*****
        * FIND RESULT
        *****/
        $result = $this->db->find($array);
        
        return new mongo_result($result);
    }
    
}

class mongo_result{
    
    
}