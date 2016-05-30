<?php

class test extends model{
    
    
    
    public $id;
    public $name;
    public $options;
    
    
    
    
    private $directive = array(
        'id' => 'int(10)',
        'options'=>'var_char(100)'
    )
}
