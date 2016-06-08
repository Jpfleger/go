<?php
class session {
    
    /**
     * GENERATE THE SESSION TABLE IF NON EXISTS AND REMOVE OLD SESSIONS
     * @private
     */
    public function __construct(){
        /*****
        * GET THE CONFIG
        *****/
        $c = config::get_config();
        
        /*****
        * IF NOT A GATED APPLICATION, RETURN
        *****/
        if(!$c->GATED) return;
        
        /*****
        * GET THE DB FILE
        *****/
        $db = db::get_db();
        
        /*****
        * CHECK FOR TABLE
        *****/
        $res = $db->con->query('SHOW TABLES LIKE "'.$c->DATABASE_SESSION_TABLE_NAME.'"');
        
        /*****
        * CHECK FOR TABLE AND CREATE IT
        *****/
        if($res->num_rows == 0){  
            mysqli_free_result($res);
            $this->create_session_table();
                
            /*****
            * START SESSION AND RETURN
            *****/
            
            session_start();
            return;
        }
        
        /*****
        * CHECK FOR OLD SESSIONS AND REMOVE
        *****/
        $exp = time() - $c->MAX_SESSION_HOURS*60*60;
        $db->con->query('DELETE FROM '.$c->DATABASE_SESSION_TABLE_NAME.' WHERE timestamp <'.$exp);
        
        /*****
        * START SESSION AND RETURN
        *****/
        session_start();
        return;
    }
    
    /**
     * DESTROY SESSION
     */
    public function destroy(){
        
        /*****
        * GET DB
        *****/
        $db = db::get_db();
        
        /*****
        * GET CONFIG
        *****/
        $c = config::get_config();
        
        /*****
        * REMOVE FROM DATABASE
        *****/
        $db->con->query("DELETE FROM ".$c->DATABASE_SESSION_TABLE_NAME." WHERE session_id='".$_SESSION[$c->SESSION_NAME]."'");
        
        /*****
        * DESTROY SESSION FROM SERVER
        *****/
        session_unset($_SESSION[$c->SESSION_NAME]);
        session_destroy();
    }
    
    /**
     * GRABS THE DATA FROM THE SESSION
     * @return array ARRAY OF STORED DATA
     */
    public function data(){
        /*****
        * GET DB
        *****/
        $db = db::get_db();
        
        /*****
        * GET CONFIG
        *****/
        $c = config::get_config();
        
        /*****
        * GET THE DATA
        *****/
        $res = $db->con->query('SELECT data FROM '.$c->DATABASE_SESSION_TABLE_NAME.' WHERE session_id = "'.$_SESSION[$c->SESSION_NAME].'"');
        
        while ($obj = mysqli_fetch_assoc($res)){
            $ret[] = $obj;
        }        
        if(isset($ret[0]['data'])){
            return json_decode($ret[0]['data']);
        }
    }
    
    /**
     * SET A SESSIONG
     * @param array [$data = NULL] DATA TO BE STORED IN THE SESSION
     */
    public function set($data = NULL){
        
        /*****
        * GET DB
        *****/
        $db = db::get_db();
        
        /*****
        * GET CONFIG
        *****/
        $c = config::get_config();
        
        /*****
        * GENERATE A UNIQUE SESSION ID
        *****/
        $chars = '1234567890abcdefghijklmnopqrstuvwxyz!@#$%^&*()_+?|}{][<>.,';

        for ($p = 0; $p < 40; $p++){
            $session_id .= ($p%2) ? $chars[mt_rand(19, 40)] : $chars[mt_rand(0, 18)];
        }
        
        /***
        * IF DATA, CONVERT TO JSON
        ****/
        if(!is_null($data)){
            $data = json_encode($data);
        }
        
        /*****
        * GET IP ADDRESS
        *****/
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        /*****
        * INSERT INTO THE DATABASE
        *****/
        $db->con->query("INSERT INTO ".$c->DATABASE_SESSION_TABLE_NAME." (session_id,ip_address,timestamp,data) VALUES('".$session_id."','".$ip."',".time().",'".$data."')");
            
        $_SESSION[$c->SESSION_NAME] = $session_id;
    }
    
    /**
     * CHECK THE SESSION
     * @return boolean SESSION EXIST OR NOT
     */
    public function check(){
        /*****
        * GET DB
        *****/
        $db = db::get_db();
        
        /*****
        * GET CONFIG
        *****/
        $c = config::get_config();
        
        /******
        * SESSION NOT SET ON SERVER
        ******/
        if(!isset($_SESSION[$c->SESSION_NAME])){
            return false;
        }

        /******
        * GRAB SESSION FROM THE SERVER
        ******/
        $res = $db->con->query("SELECT * FROM ".$c->DATABASE_SESSION_TABLE_NAME." WHERE session_id ='".$_SESSION[$c->SESSION_NAME]."'");

        /******
        * SESSION NOT SET IN DATABASE
        ******/
        if(!$res->num_rows){
            return false;
        }
        
        /******
        * SESSION ON SERVER AND IN DB RETURN TRUE
        ******/
        return true;
    }
    
    /**
     * CREATE THE SESSION TABLE
     */
    private function create_session_table(){
        /*****
        * GET THE CONFIG
        *****/    
        $c = config::get_config();
        
        /*****
        * GET THE DB FILE
        *****/
        $db = db::get_db();
        
        /*****
        * CREATE SQL STATEMENT
        *****/
        $sql ="CREATE TABLE ".$c->DATABASE_SESSION_TABLE_NAME." (
            session_id VARCHAR(255) PRIMARY KEY,
            ip_address VARCHAR(255),
            timestamp VARCHAR(255),
            data VARCHAR(2000)
            )";
        
        /*****
        * MSQL QUERY
        *****/
        $db->con->query($sql);
        if($db->con->error){
            echo $db->con->error;
        }
        return;
    }
    
}