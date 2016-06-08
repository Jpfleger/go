<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if(isset($_POST['generate'])){
        
        $opp = "<?php".PHP_EOL."config::get_config([".PHP_EOL;
        $opp .= "'DATABASE' => '',".PHP_EOL;
        $opp .= "'HOSTNAME' => '',".PHP_EOL;
        $opp .= "'USERNAME' => '',".PHP_EOL;
        $opp .= "'PASSWORD' => '',".PHP_EOL;
        $opp .= "'DEFAULT_CONTROLLER' => 'home',".PHP_EOL;
        $opp .= "'GATED' => FALSE,".PHP_EOL;
        $opp .= "'NON_GATED_CONTROLLERS' => array()".PHP_EOL;
        $opp .= "'GATED_FALLBACK_CONTROLLER' => ''".PHP_EOL;
        $opp .= "'DATABASE_SESSION_TABLE_NAME' => 'go_session'".PHP_EOL;
        $opp .= "'SESSION_NAME' => 'go_ses'".PHP_EOL;
        $opp .= "'MONGODB' => FALSE".PHP_EOL;
        $opp .= "'MONGO_DB_NAME' => ''".PHP_EOL;
        $opp .= "'MONGO_LOCATION' => ''".PHP_EOL;
        
        $opp .= "]);";
        
        //WRITE THE CONFIG
        $config = fopen(BASEPATH.'system/core/config.php','w');
        fwrite($config,$opp);
        fclose($config);
        
        header('Location: /');
    }
?>
<html>
<head>
    </head>
    <body>
        <div style="width:80%;margin:auto;">
            <div style="margin-top:20px;">
            <h2>Welcome to Go</h2>
            </div>
            <div style="margin-top:20px;">
                <form method="post" action="">
                    <input type="submit" name="generate" value="GENERATE THE CONFIG">
                </form>
            </div>
        </div>
    </body>
</html>