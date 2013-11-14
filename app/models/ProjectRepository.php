<?php

interface ProjectRepository
{
    public function names();
    
    public function find($project);
}

