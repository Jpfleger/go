<?php
class load{
    /**
     * LOAD A VIEW
     * @param string $view PATH TO THE VIEW FROM THE APP/VIEW/ DIR
     */
    public function view($view){
            require_once('./app/view/'.$view);
    }
}