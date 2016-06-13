<?php

/********
* ALERT CLASS HANDLES ALERTS
********/

class alert{
    
    
    /**
     * ERROR OUT
     * @param string $error ERROR TO BE DISPLATED
     */
    public function error($error){
        echo '<div style="display:block;background-color:lightred;font-weight:600;font-size:14pt;">';
        echo '<pre>'.$error.'</pre>';
        echo '</div>';
    }
    
    /**
     * DEBUG
     * @param string $error DEBUG TO BE DISPLATED
     */
    public function debug($error){
        echo '<div style="display:block;background-color:lightgrey;font-weight:600;font-size:14pt;">';
        echo '<pre>'.$error.'</pre>';
        echo '</div>';
    }
    
}