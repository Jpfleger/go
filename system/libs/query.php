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
        $this->where = $string;
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
                
            /**
            * INSERT
            **/
            case 'INSERT':
                //NO NEED FOR RESULTS
                $return = false;
                
                $fields = array_keys($this->data);
                $sql = 'INSERT INTO '.$this->table.' ('.implode(', ',$fields).') VALUES(';
                foreach($this->data as $k => $v){
                    if(is_numeric($v)){
                        $vals[] = $v;
                    }else{
                        $vals[] = '"'.$v.'"';
                    }
                }
                
                $sql .= implode(',',$vals ).')';
            break;
                
            /**
            * UPDATE
            **/    
            case 'UPDATE':
                //NO NEED FOR RESULTS
                $return = false;
                
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
                
            /**
            * SELECT
            **/    
            case 'SELECT':
                $return = true;
                $sql = 'SELECT '.$this->fields.' FROM '.$this->table;
                if( isset($this->where) && is_array($this->where) ){
                    
                    /***
                    * WHERE IS AN ARRAY SORT DATA
                    ****/
                    foreach($this->where as $field => $value){
                        if( is_numeric($value) ){
                            $data[] = $field.'='.$value;
                        }else{
                            $data[] = $field.'="'.$value.'"';
                        }
                    }
                    
                    /***
                     * BUILD WHERE CLAUSE
                    ****/
                    $sql .=' WHERE '.implode('AND ',$data);
                    
                    
                    
                }else if( isset( $this->where ) ){
                    /***
                    * WHERE IS A STRING
                    ****/
                    $sql .=' WHERE '.$this->where;
                }
            break;
            /**
            * DELETE
            **/
            case 'DELETE':
                //NO NEED FOR RESULTS
                $return = false;
                $sql = 'DELETE FROM '.$this->table.' WHERE '.$this->where;
            break;
        }
        //UNSET QUERY TYPE
        unset($this->query_type,$this->table,$this->where,$this->fields,$this->data);
        //RETURN THE QUERY RESULT
        return $this->sql($sql,$return);
    }
    
    
    /**
     * SQL FUNCTION TO HANDLE A STRAIGH SQL CALL
     * @param  string $query SQL QUERY STRING
     * @return object RETURNS FORMATED ARRAY
     */
    public function sql($query,$return){
        /******
        *GET THE DATABASE CONNECTION
        ******/
        $db = db::get_db();
        
        /******
        *GET GO
        ******/
        $go = go::get_go();
        
        /****
        * ADD QUERIES TO GO FOR DEBUGGING
        * ***/
        $go->queries[] = $query;
        
        /***
        * SEND QUERY THROUGH TO THE DATABASE
        ****/
        $res = $db->con->query($query);
        
        /****
        * CHECK FOR ERROR AND DISPLAY IF ERROR
        * ***/
        if($db->con->error){
            
            /*******
            * RETURN ERROR
            * ****/

            return (object)['result'=>false,'error'=>$db->con->error];
        }
        
        /****
        *RETURN IF NO RESULT NEEDED
        ***/
        
        if(!$return) return;
        
        /***
        * RETURN FORMATED RESULT
        * 
        ****/
        $qr = new query_result($res);
        $qr->query = $query;
        
        /****
        *SET RESULT TO TRUE
        ****/
        $qr->result = true;
        
        /****
         *SET QUERY ID
        ***/
        $qr->id = mysqli_insert_id($db->con);
        
        /***
        *FREE RESULT
        ***/
        mysqli_free_result($res);
        
        /***
        *RETURN THE RESULTS
        ****/
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
        $this->records = $this->format($res);
        
        /****
        * GRAB FIRST RESULT AND PLACE IT IN ROW
        *****/
        $this->row = isset( $this->records[0] ) ? $this->records[0] : false;
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