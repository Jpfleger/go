<?php

class controller{
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        $this->go = go::get_go(); 
    }
}

class view{
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        $this->go = go::get_go(); 
    }
}

class model{
    /**
     * CONSTRUCTOR FUNCTION GETS GO
     * @private
     */
    public function __construct(){
        /**
        *GET THE SUPER OBJECT
        */
        $this->go = go::get_go();
        
        /****
        *GRAB THE NAME OF THE MODEL
        ****/
        $this->model = get_class($this);
        
        /****
        * CHECK THE DATABASE TO MAKE SURE THE DATABASE EXISTS
        ***/
        $this->check_database();
        
    }
    
    
    /**
     * CHECK THE DATABASE AND BUILD THE TABLE IF NECESSARY
     */
    private function check_database(){
            
        /*****
        * IF NOT IN DATABSE, BUILD TABLE
        *****/
        $result = $this->go->db->straight_query('SHOW COLUMNS FROM '.$this->model);
        
        /**
        * NOW TABLE FOR THIS CALL IN THE DATABASE
        * BUILD IF EMPTY
        ***/
        if(empty($result)){
            $this->build_me();    
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
        echo '<pre>Creating: '.$sql.' ('.implode(', ',$sql_fields).')'.'</pre>';
        $RES = $this->go->db->straight_query( $sql.' ('.implode(', ',$sql_fields).')' );
    }
    
    
}