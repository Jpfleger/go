<?php

class query{
    /*****
    *GET THE DATABASE CONNECTION
    *****/
    private $db; 
    private $qc;
    
    public function __construct(){
        /******
        *GET THE DATABASE CONNECTION
        ******/
        $this->db = db::get_db();
    }
        
    
    /*******
    *PUBLIC LINKED FUNCTIONS
    *START QUERY: SELECT, INSERT, UPDATE, DELETE
    *******/

    /**
     * SELECT LINKED FUNCTION
     * @param  string $string FIELDS TO BE SELECTED
     * @return object THIS
     */
    public function select($string){
        
        return $this;
    }
    
    /**
     * INSERT LINKED FUNCTIN
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function insert($array){

        return $this;
    }
    
    /**
     * UPDATE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function update($array){

        return $this;
    }
    
    /**
     * DELETE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function delete($array){

        return $this;
    }
    
    
    
    /**
     * SQL FUNCTION TO HANDLE A STRAIGH SQL CALL
     * @param  string $query SQL QUERY STRING
     * @return object RETURNS FORMATED ARRAY
     */
    public function sql($query){
        
        
        /****
        * ADD QUERIES TO GO FOR DEBUGGING
        * ***/
        $this->go->queries[] = $query;
        
        
        /***
        * SEND QUERY THROUGH TO THE DATABASE
        ****/
        $res = $this->db->con->query($query);
        
        
        /****
        * CHECK FOR ERROR AND DISPLAY IF ERROR
        * ***/
        if($this->db->con->error){
            
            /*******
            * ALERT ERROR
            * ****/
            echo $this->db->con->error;
            die();
        }
        
        /***
        * RETURN FORMATED RESULT
        ****/
        $qr = new query_result($res);
        $qr->query = $query;
        
        return $qr;
    }
}

/*********
* QUERY RESULTS CLASS TO HANDLE THE RESULTS
*********/

class query_result {
    
    /**
    * RESULT ARRAY
    **/
    public $records;
    
    /**
    * MODELS ARRAY
    **/ 
    public $models;
    
    
    public function __construct($res,$query){
        
        /****
        * FORMAT RESULT INTO ARRAY AND ADD TO THE RECORDS VAR
        *****/
        $this->query = $query;
        
        /****
        * FORMAT RESULT INTO ARRAY AND ADD TO THE RECORDS VAR
        *****/
        $this->records = $this->format($res);
    }
    
    /**
     * MODELIZE WILL TURN RESULTS INTO ACITONABLE MODELS
     * @param string $table REQUIRED TO HAVE THE NAME OF THE TABLE 
     */
    public function modelize($table){
        
        /***
        * LOOP RESULTS AND INSTANTIATE MODEL
        ****/
        foreach($this->result as $k => $v){
            
            $x = new $table;
            
            /***
            * LOOP FIELDS AND VALUES INTO THE MODEL
            ****/
            foreach($v as $field => $value){
                $x->{$field} = $value;
            }
            
            /***
            * ADD OBJECT TO THE MODEL ARRAY
            ****/
            $this->model[] = $x;
        }      
    }
    
    /**
     * FORMAT THE RESULT INTO A READABLE ARRAY
     * @param  object $res RESULT FROM THE QUERY
     * @return array RESULT OF THE QUERY IN FORMATED ARRAY
     */
    private function format($res){
        /**
        * SET ARRAY AND NEW CLASS FOR RESULT
        *****/
        $ret = array();

        
        /*****
        * CHECK FOR RESULTS, IF NONE, RETURN EMPTY ARRAY
        ******/
        if($res->num_rows == 0){
            return $ret;
        }
        
        /****
        * FETCH RESULTS
        ****/
        while ($obj = mysqli_fetch_assoc($res)){
            $ret[] = $obj;
        }
        
        return $ret;;
    }
}