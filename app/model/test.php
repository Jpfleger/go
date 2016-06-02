<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends model{
    
    
    
    public $id;
    public $name;
    public $options;
    public $another;
    public $another2;
    public $another3;
    
    
    
    protected $field_options = array(
        'id' => 'int(10) AUTO_INCREMENT',
        'options'=>'varchar(100)',
        'another2'=>'varchar(1000)',
        'primary'=>'id'
    );
    
}
