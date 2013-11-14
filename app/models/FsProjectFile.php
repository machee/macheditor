<?php

class FsProjectFile implements ProjectFile
{
    private $file;
    private $baseDir;
    
    public function __construct($file, $baseDir)
    {
        $this->file    = $file;
        $this->baseDir = $baseDir;
    }

    public function getContents()
    {
        $path = $this->path();
        
        if ( ! file_exists($path)) {
            return '';
        }
        
        if ( ! (is_file($path) && is_readable($path))) {
            return false;
        }

        return file_get_contents($this->path());
    }

    public function setContents($contents)
    {
        $projDir  = "$this->baseDir/";
        $filePath = $this->file;

        $newFile      = ! file_exists($projDir.$filePath);
        $existingPath = '';
        $createdPath  = [];
        
        if (!is_dir($projDir)) {
            return [
                'message' => 'projDir does not exist or is not a directory',
                'error'   => true,
            ];
        }
        
        $parts = explode('/', $filePath);
        $path  = '';
        
        $fileName = array_pop($parts);
        
        if (!is_writable($projDir.$filePath)) {
            if (!$newFile) {
                return [
                    'message' => 'file is not writable',
                    'error'   => true,
                ];
            }
            
            foreach($parts as $part) {
                $path .= $part;
                
                if (file_exists($projDir.$path)) {
                    if (!is_dir($projDir.$path)) {
                        return [
                            'message' => 'part of path is not a directory',
                            'error'   => true,
                        ];
                    }
                    
                    $existingPath = $path;
                } else {
                    mkdir($projDir.$path);
                    $createdPath[] = $part;
                }
                
                $path .= '/';
            }
        } elseif ($newFile) {
            $existingPath = implode('/', $parts);
        }
        
        $createdPath[] = $fileName;
        
        try {
            file_put_contents($projDir.$filePath, Input::get('content'));
        } catch (Exception $e) {
            return [
                'message' => 'could not write file',
                'projDir' => $projDir,
                'file'    => $filePath,
                'error'   => true,
            ];
        }
        
        $response = ['message'=> 'success', 'new'=> $newFile, 'error' => false];
        
        if ($newFile) {
            $response['existingPath'] = $existingPath;
            $response['createdPath']  = $createdPath;
        }
        
        return $response;
    }

    public function delete()
    {
        $path = $this->path();
        
        if (is_dir($path)) {
            exec("rm -rf $path > /dev/null", $output, $return);
            if (0 !== $return) {
                return [
                    'message' => "could not delete directory ($return)",
                    'error'   => true,
                ];                
            }
        } else {
            if ( ! unlink($path)) {
                return [
                    'message' => 'could not delete file',
                    'error'   => true,
                ];
            }
        }
        
        return ['message' => 'deleted', 'error' => false];
    }

    protected function path()
    {
        return "$this->baseDir/$this->file";
    }
}
