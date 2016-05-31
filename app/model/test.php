<?php

class test extends model{
    
    
    
    public $id;
    public $name;
    public $options;
    
    
    
    
    protected $field_options = array(
        'id' => 'int(10) AUTO_INCREMENT',
        'options'=>'varchar(100)',
        'primary'=>'id'
    );
    
}
