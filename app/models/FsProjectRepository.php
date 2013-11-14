<?php

class FsProjectRepository implements ProjectRepository
{
    public function names()
    {
        $baseDir = $this->baseDir();
        
        $projects = scandir($baseDir);
        unset($projects[0]); // .
        unset($projects[1]); // ..
        
        return $projects;
    }
    
    public function find($project)
    {
        $baseDir = $this->baseDir()."/$project";
        
        if (!is_dir($baseDir)) {
            return false;
        }
        
        return new FsProject($project);
    }
    
    private function baseDir()
    {
        return storage_path()."/projects";
    }
}

