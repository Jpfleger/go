<?php
/**********
* DATABASE CLASS
**********/ 
class db {
    
    public function __construct(){
        //CONNECT TO THE DATABASE
        $this->connect();
    }
    
    /**
     * CONNECT THE THE DATABASE
     */
    private function connect(){
        //CREATE CONNECTION VARIABLE
        $c = config::get_config();
        $this->con = mysqli_connect($c->HOSTNAME, $c->USERNAME, $c->PASSWORD,$c->DATABASE);
        
        //CONNECTION ISSUE?
        if(!$this->con){
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        } 
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
        $this->q = new stdclass;
        $this->q->protocall = 'select';
        $this->q->select = $string;
        return $this;
    }
    
    /**
     * INSERT LINKED FUNCTIN
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function insert($array){
        $this->q = new stdclass;
        $this->q->protocall = 'insert';
        $this->q->insert = $array;
        return $this;
    }
    
    /**
     * UPDATE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function update($array){
        $this->q = new stdclass;
        $this->q->protocall = 'update';
        $this->q->update = $array;
        return $this;
    }
    
    /**
     * DELETE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function delete($array){
        $this->q = new stdclass;
        $this->q->protocall = 'delete';
        $this->q->update = $array;
        return $this;
    }
    
    /*****
    *LINKED PUBLIC FUNCTIONS
    *SELECT TABLE
    *****/
    
    /**
     * FROM LINKED FUNCTION
     * @param  string $table TABLE TO SELECT
     * @return object THIS
     */
    public function from($table){
        $this->q->table = $table;
        return $this;
    }
    
    /**
     * IN LINKED FUNCTIN
     * @param  string $table TABLE TO BE SELECTED
     * @return object THIS
     */
    public function in($table){
        $this->q->table = $table;
        return $this;
    }
    
    /**
     * INTO LINKED FUNCTION
     * @param  string $table TABLE TO BE SELECTED
     * @return object THIS
     */
    public function into($table){
        $this->q->table = $table;
        return $this;
    }
    
    /*****
    *LINKED PUBLIC FUNCTIONS
    *WHERE STATEMENT OR UPDATE CRITERIA
    *****/
    
    public function where($crit){
        $this->q->where = $crit;
        return $this;
    }
    
    /*****
    *LINKED PUBLIC FUNCTIONS
    *EXECUTE
    *ROW -> SINGLE RECORD | RESULT -> MULTIPLE RECORDS
    *****/
    
    public function row(){
        
        switch($this->q->protocall){
            case 'insert':
                
            break;
            case 'delete':
            break;
            case 'select':
            break;
            case 'update':
            break;
        }
        
    }
    
    public function result(){
        
        switch($this->q->protocall){
            case 'insert':
            break;
            case 'delete':
            break;
            case 'select':
            break;
            case 'update':
            break;
        }
        
    }
    
    /**
     * STRAIGHT QUERY OR SIMPLE QUERY OUTSIDE THE LINKED FUNCTION
     * @param  string $query         QUERY TO BE EXECUTED
     * @param  boolean [$raw = false] RETURN RAW OR FORMATED RESULTS
     * @return array RESULT OF THE QUERY FORMATED OR UNFORMATED
     */
    public function straight_query($query,$raw = false){
        
        /***
        * SEND QUERY THROUGH TO THE DATABASE
        ****/
        $res = $this->con->query($query);
        
        /****
        * CHECK FOR FORMAT
        *****/
        if($raw){
            return $res;
        }
        
        return $this->format($res);
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