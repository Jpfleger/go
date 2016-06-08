<?php

class query{
    
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
        $this->query_type = 'SELECT';
        $this->fields = $string;
        return $this;
    }
    
    /**
     * INSERT LINKED FUNCTIN
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function insert($array){
        $this->query_type = 'INSERT';
        $this->data = $array;
        return $this;
    }
    
    /**
     * UPDATE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function update($array){
        $this->query_type = 'UPDATE';
        $this->data = $array;
        return $this;
    }
    
    /**
     * DELETE LINKED FUNCTION
     * @param  array $array ASSOC ARRAY
     * @return object THIS
     */
    public function delete($array){
        $this->data = $array;
        $this->query_type = 'DELETE';
        return $this;
    }
    
    /**
     * TABLE SETTING FUNCTIONS
     * @param  sting $table TABLE TO INSERT
     * @return object THIS
     */
    public function into($table){
        $this->table = $table;
        return $this;
    }
    
    public function in($table){
        $this->table = $table;
        return $this;
    }
    
    public function from($table){
        $this->table = $table;
        return $this;
    }
    
    
    /**
     * WHERE CLAUSE
     * @param  string $string NOTHING FANCY, JUST THE WHERE CLAUSE
     * @return object THIS
     */
    public function where($string){
        $this->sql_statement .= ' WHERE '.$string;
        return $this;
    }
    
    
    
    
    /**
     * BUILD SQL STATEMENT, FIRE AND RETURN QUERY RESULT OBJECT
     * @return object QUERY RESULT OBJECT
     */
    public function go(){
        
        /***
        * SWTICH STATEMENT FOR QUERY TYPE
        ****/
        switch($this->query_type){
                
            
            case 'INSERT':
                
                $fields = array_keys($this->data);
                $sql = 'INSERT INTO '.$this->table.' ( '.implode(', ',$fields).') VALUES(';

                foreach($this->data as $k => $v){
                    if(is_numeric($v)){
                        $sql .= ' '.$v;
                    }else{
                        $sql .= ' "'.$v.'"';
                    }
                }

                $sql .=')';
            break;
                
            case 'UPDATE':
                $fields = array_keys($this->data);
                foreach($this->data as $k => $v){
                    $change = $k.'=';
                    if(is_numeric($v)){
                        $change .=$v;
                    }else{
                        $change .='"'.$v.'"';
                    }
                    $updates[] = $change;
                }
                $sql = 'UPDATE '.$this->table.' SET '.implode(', ',$updates);
                
                if(isset($this->where)){
                    $sql .= ' WHERE '.$this->where;
                }    
            break;
                
            case 'SELECT':
                $sql = 'SELECT '.$this->fields.' FROM '.$this->table;
                if(isset($this->where)){
                    $sql .=' WHERE '.$this->where;
                }
            break;
            case 'DELETE':
                $sql = 'DELETE FROM '.$this->table.' WHERE '.$this->where;
            break;
        }
        
        return $this->sql($sql);
    }
    
    
    /**
     * SQL FUNCTION TO HANDLE A STRAIGH SQL CALL
     * @param  string $query SQL QUERY STRING
     * @return object RETURNS FORMATED ARRAY
     */
    public function sql($query){
        
        /******
        *GET THE DATABASE CONNECTION
        ******/
        $db = db::get_db();
        
        /****
        * ADD QUERIES TO GO FOR DEBUGGING
        * ***/
        $this->go->queries[] = $query;
        
        
        /***
        * SEND QUERY THROUGH TO THE DATABASE
        ****/
        $res = $db->con->query($query);
        
        
        /****
        * CHECK FOR ERROR AND DISPLAY IF ERROR
        * ***/
        if($db->con->error){
            
            /*******
            * ALERT ERROR
            * ****/
            echo $db->con->error;
            die();
        }
        
        /***
        * RETURN FORMATED RESULT
        ****/
        $qr = new query_result($res);
        $qr->query = $query;
        
        /***
        *FREE RESULT
        ***/
        mysqli_free_result($res);
        
        return $qr;
    }
}

/*********
* QUERY RESULTS CLASS TO HANDLE THE RESULTS
*********/

class query_result {

    
    public function __construct($res){
        
        /****
        * FORMAT RESULT INTO ARRAY AND ADD TO THE RECORDS VAR
        *****/
        $this->query = $query;
        
        /****
        * FORMAT RESULT INTO ARRAY AND ADD TO THE RECORDS VAR
        *****/
        $this->records = $this->format($res);
    }
    
    
    /****
     * MODELIZE WILL TURN RESULTS INTO ACITONABLE MODELS
     * @param string $table REQUIRED TO HAVE THE NAME OF THE TABLE 
     ***/
    public function modelize($table){
        
        /***
        * CHECK TO MAKE SURE THERE ARE RECORDS TO BE TURNED INTO MODEL
        ****/
        if(empty($this->records)){
            $this->models = [];
            return;
        }
        
        /***
        * LOOP RESULTS AND INSTANTIATE MODEL
        ****/
        foreach($this->records as $k => $v){
            
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