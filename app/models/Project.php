<?php

interface Project
{
    public function __construct($name);
    
    public function getName();
    
    public function files($dir = '');
}

