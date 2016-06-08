<html>
    <head>
        <?= $go->load->jquery(); ?>
        <?= $go->load->bootstrap(); ?>
        <?= $go->load->css('style'); ?>
    </head>
    <body>
        <div class="container">
            <h2>Welcome to Go</h2>
            <strong>
                You will need to set the permissions of the config file (best to chown to your ownership) and set the config file.<br/>
            Then fill out the config array to match your dev environment.
            <br />    
                when launching. move all but config.php to the live environment and regenerate the config 
            </strong>
        </div>
    </body>
</html>