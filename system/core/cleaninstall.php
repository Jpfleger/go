<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    if(isset($_POST['submit'])){
        
        $opp = "<?php config::get_config([";
        foreach($_POST as $s => $v){
            $opp .= "'".$s."'".'=>'."'".$v."',".PHP_EOL;
        }
        $opp .= "]);";
        $config = fopen(BASEPATH.'system/core/config.php','w');
        fwrite($config,$opp);
        fclose($config);
        header('Location: /');
    }

    require_once(BASEPATH.'system/core/settings.php');

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
                <form method="post">
                    <?php foreach($settings as $k => $v) :?>
                    <input  style="width:100%;height:20px;" type="text" name="<?=$v?>" placeholder="<?=$v?>"><br />
                    <?php endforeach ;?>
                    <input type="submit" name="submit" value="go">
                </form>
                
            </div>
        </div>
    </body>
</html>