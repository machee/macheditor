<?php

class FsProject implements Project, ProjectFileRepository
{
    private $name;
    private $baseDir;
    private $inSymLinks = [];
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->baseDir = storage_path()."/projects/$name";
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function files($dir = '')
    {
        if ($dir != '' && realpath($this->baseDir) == realpath("$this->baseDir$dir")) {
            return array();
        }

        $dirFiles = scandir("$this->baseDir$dir");
        unset($dirFiles[0]); // .
        unset($dirFiles[1]); // ..

        $dirs  = array();
        $files = array();

        foreach ($dirFiles as $file) {
            $path     = ltrim("$dir/$file", '/');
            $fullPath = "$this->baseDir/$path";
            
            if (!is_dir($fullPath)) {
                $files[$file] = $path;
                continue;
            }

            if (in_array($file, array('.git'))) {
                continue;
            }
            
            if (is_link($fullPath)) {
                if (in_array($fullPath, $this->inSymLinks)) {
                    continue;
                }
                $this->inSymLinks[] = $fullPath;
            }

            $dirs[$file] = array(
                'files' => $this->files("/$path"),
                'path'  => $path,
            );

            if (is_link($fullPath)) {
                array_pop($this->inSymLinks);
            }
        }

        return array_merge($dirs, $files);
    }

    public function findFile($filePath)
    {
        return new FsProjectFile($filePath, $this->baseDir);
    }
}

