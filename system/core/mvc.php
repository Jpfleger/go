<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class controller{
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        $this->go = go::get_go(); 
        $this->config = config::get_config();
    }
}

class view{
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        $this->go = go::get_go(); 
        $this->config = config::get_config();
    }
}

class model{
    
    private $primary = 'id';
    
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        
        /****
        *GRAB THE NAME OF THE MODEL
        ****/
        $this->model = get_class($this);
        
        /****
        * CHECK THE DATABASE TO MAKE SURE THE DATABASE EXISTS
        ***/
        $this->check_database();
        
        /****
        * SET THE PRIMARY KEY
        *****/
        if(isset($this->field_options['primary'])){
            $this->primary = $this->field_options['primary'];
        }

        /**
        * UNSET THE FIELD OPTIONS IF THEY EXIST
        ***/
        if(isset($this->field_options)){
            
            unset($this->field_options);    
        }
        
    }
    
    /**
     * GETS A RECORD BY A PRIMARY KEY
     * BY DEFAULT, GO WILL SEARCH FOR ANY PRIMARY KEY 
     * DECLARED IN A PRIMARY 
     */
    public function get($id){
        
    }
    
    /**
     * SIMPLE ROUTING FUNCTION
     */
    public function save(){
        
        /***
        * CHECK FOR PRIMARY IN FIELD OPTIONS
        * IF ID ISSET UPDATE, OTHERWISE CREATE
        ****/
        if( isset($this->{$this->primary})){
            $this->update();
        }
        else{
            $this->create();
        }
    }
    
    /**
     * INSERT THE MODEL INTO THE DATABSE
     */
    private function create(){
        
        /*****
        * GET TABLE FIELDS
        ******/
        $table_fields = get_class_vars(get_class($this));
        
        /****
        * UNSET SOME FIELDS
        ****/
        unset($table_fields['primary'],$table_fields['field_options']);
        
        /****
        * LOOP THROUGH THE TABLES AND CREATE DATA FOR QUERY CALL
        ****/
        foreach($table_fields as $field => $null){
            if(isset($this->{$field})){   
                $data[$field] = $this->{$field};
            }
        }
        
        /***
        * PUSH INTO DATABASE
        ****/
        $go = go::get_go();
        $go->query->insert($data)->into(get_class($this))->go();
    }
    
    
    private function update(){
        
        /*****
        * GET TABLE FIELDS
        ******/
        $table_fields = get_class_vars(get_class($this));
        
        /****
        * UNSET SOME FIELDS
        ****/
        unset($table_fields['primary'],$table_fields['field_options']);
        
        /****
        * LOOP THROUGH THE TABLES AND CREATE DATA FOR QUERY CALL
        ****/
        foreach($table_fields as $field => $null){
            if(isset($this->{$field})){
                $data[$field] = $this->{$field};
            }
            
        }
        /***
        * PUSH INTO DATABASE
        ****/
        $go = go::get_go();
        $go->query->update($data)->in(get_class($this))->go();
    }
    /******
    * AUTOMATIC DATABASE MAKER FUNCTIONS
    * THESE WILL CREATE A DATABASE TABLE FOR ANY MODEL CLASS
    * OR UPDATE THE FIELDS IN AN ALREADY CREATED DATABASE TO REFLECT THE MODEL CLASS
    ********/
    
    /**
     * CHECK THE DATABASE AND BUILD THE TABLE IF NECESSARY
     */
    private function check_database(){
            
        /*****
        * IF NOT IN DATABSE, BUILD TABLE
        *****/
        $db = db::get_db();
        $result = $db->con->query('SHOW COLUMNS FROM '.$this->model);
        
        /**
        * NOW TABLE FOR THIS CALL IN THE DATABASE
        * BUILD IF EMPTY
        ***/
        if(empty($result)){
            $this->build_me();
            return;
        }
        
        /***
        * FORMAT THE RESULTS TO PLACE THE FILED NAME AS THE KEY IN THE ARRAY
        *****/
        
        foreach($result as $k => $v){
            $fields_array[strtolower($v['Field'])] = $v;
        }
                
        /**
        * CHECK THE FIELDS IN THE CLASS AGAINST THE TABLE
        * IF THERE ARE CHANGES, MAKE THEM
        *****/
        $fields = get_class_vars(get_class($this));
        unset($fields['field_options']);
        
        /**
        * LOOP THROUGH THE FIELDS CHECK FOR
        * FIELD OPTIONS
        * ***/
        foreach($fields as $field => $f){
            /***
            *CHECK FOR FIELD EXISTS
            ***/
            
            if(!isset($fields_array[$field]) && strtolower($field) != 'primary'){
                /******
                * NO FIELD IS IN DB, MAKE A NEW FIELD
                ******/
                $sql = 'ALTER TABLE '. get_class($this) .' ADD '.$field;
                
                /******
                * CHECK FOR FIELD OPTIONS
                ******/
                if(isset($this->field_options[$field])){
                    $sql .=' '.$this->field_options[$field];
                }else{
                    $sql .=' VARCHAR(255)';
                }
                
                /*****
                * CREARE THE FIELD
                ******/
                echo '<pre>Altering Table</pre> '.$sql;
                $db->con->query($sql);
                
            }else{
                
                /****
                * FIELD EXISTS, MAKE SURE THERE AREN'T ANY CHANGES TO THE OPTIONS
                * TO COME 
                ****/
                
            }
            
        }
        
        
        
        
        
        
         
    }
    
    /**
     * BUILDS THE DATABASE TABLE
     */
    private function build_me(){
        
        /**
        *GET THE FIELDS IN THE MODEL CLASS
        **/
        $fields = get_class_vars(get_class($this));

        /***
        * BUILD SQL VAR
        ****/
        $sql = 'CREATE TABLE '.$this->model.' ';
        
        if($this->field_options){
            
            /*****
            * HAS FIELD OPTIONS
            * UNSET THE FIELD OPTIONS IN THE FIELD ARRAY
            *****/ 
            unset($fields['field_options']);
            
            /***
            * LOOP THE FIELDS AND BUILD THE QUERY
            ****/
            foreach($fields as $field => $v){
                if(isset($this->field_options[$field])){
                    
                    /****
                    *HAS DIRECTIVE USE DIRECTIVE OPTIONS
                    ****/
                    $sql_fields[] = $field.' '.$this->field_options[$field];
                    
                }else{
                    
                    /****
                    *HAS NO DIRECTIVE, IS ID FIELD?
                    ****/
                    if(strtolower($field) == 'id'){
                        
                        /****
                        * IS AN ID FIELD USE INT(10) AUTO_INCREMENT
                        ****/
                        $sql_fields[] = $field.' INT(10) AUTO_INCREMENT';
                        
                    }else{
                        
                       /****
                        * HAS NO DIRECTIVE, AND NOT ID FIELD
                        * MAKE AS VARCHAR
                        ****/ 
                        $sql_fields[] = $field.' VARCHAR(255)';
                        
                    }
                    
                }
            
            }
            
            /******
            * SET PRIMARY KEY
            *****/
            if(isset($this->field_options['primary'])){
                //ADD TO SQL FIELDS
                $sql_fields[] = 'PRIMARY KEY('.$this->field_options['primary'].')';
            }
        }else{
            
            /*****
            * NO FIELD OPTIONS MAKE ALL VAR CHARS EXCEPT ID
            *****/ 
            foreach($fields as $field => $v){
                
                /***
                * CHECK FOR ID FIELD
                ****/
                if(strtolower($field) == 'id'){
                    /****
                    * IS AN ID FIELD
                    * BY DEFUALT MAKE THIS FIELD A INT 10 AUTO INC AND PRIMARY KEY
                    ******/
                    
                    $sql_fields[] = $field.' INT(10) AUTO_INCREMENT';
                    $sql_fields[] = 'PRIMARY KEY('.$field.')';

                }else{
                    
                    /****
                    * CREATE SQL FIELD VAR CHAR
                    * ****/
                    $sql_fields[] = $field.' VARCHAR(255)';
                }
                
                
            }
            
        }
        
        /*****
        *BUILD THE TABLE
        ******/
        $db = db::get_db();
        echo '<pre>Creating: '.$sql.' ('.implode(', ',$sql_fields).')'.'</pre>';
        $RES = $db->con->query( $sql.' ('.implode(', ',$sql_fields).')' );
    }
    
    
}