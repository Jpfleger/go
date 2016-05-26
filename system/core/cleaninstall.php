<?php
    if(isset($_POST['submit'])){
        
        $opp = "<?php config::get_config([";
        foreach($_POST as $s => $v){
            $opp .= "'".$s."'".'=>'."'".$v."',";
        }
        $opp .= "]);";
        $opp .="$"."install=true;";
        $config = fopen('./system/core/config.php','w');
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
                <form method="post">
                    <input type="text" name="DATABASE" placeholder="DATABASE NAME"><br />
                    <input type="text" name="HOSTNAME" placeholder="HOSTNAME"><br />
                    <input type="text" name="USERNAME" placeholder="USERNAME"><br />
                    <input type="text" name="PASSWORD" placeholder="PASSWORD"><br />
                    <input type="submit" name="submit" value="go">
                </form>
                
            </div>
        </div>
    </body>
</html>