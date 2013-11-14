<?php

class Main extends BaseController
{
    /**
     * @var ProjectRepository
     */
    private $projects;
    
    public function __construct(ProjectRepository $projects)
    {
        $this->projects = $projects;
    }
    
    public function projects()
    {
        $projects = $this->projects->names();
        
        return View::make('projects', ['projects' => $projects]);
    }
    
    public function project($projectName)
    {
        $project = $this->projects->find($projectName);
        
        if ( ! $project) {
            App::abort(404);
        }
        
        $data = ['project' => $projectName, 'files' => $project->files()];
        
        $data['files'] = View::make('fragments/files', $data);
        
        return View::make('project', $data);
    }
    
    public function edit($projectName, $filePath)
    {
        $baseDir = storage_path()."/projects/$projectName";
        
        if ( ! ($project = $this->projects->find($projectName))) {
            App::abort(404);
        }
        $file    = $project->findFile($filePath);

        if ( ($content = $file->getContents()) === false ) {
            App::abort(404);
        }

        $files = View::make('fragments/files', [
            'project' => $projectName,
            'files'   => $project->files() 
        ]);
 
        return View::make('editor', [
           'project' => $projectName,
           'file'    => $filePath,
           'files'   => $files,
           'content' => htmlentities($content),
           'title'   => $filePath,
           'aceMode' => $this->getAceMode($filePath),
        ]);
    }

    protected function getAceMode($filePath)
    {
        $fileType = $filePath;
        $aceMode  = 'text';
        
        $lastDot   = strrpos($filePath, '.');
        $lastSlash = strrpos($filePath, '/');
        
        if ($lastDot !== false) {
            if ($lastSlash !== false && $lastDot < $lastSlash) {
                $fileType = substr($filePath, $lastSlash+1);
            } else {
                $fileType = substr($filePath, $lastDot);
            }
        } elseif ($lastSlash !== false) {
            $fileType = substr($filePath, $lastSlash+1);
        }
        
        // https://github.com/ajaxorg/ace/tree/master/lib/ace/mode
        if (in_array($fileType, 
            ['.php', '.json', '.css', '.html', '.xml', '.markdown']
        )) {
            $aceMode = substr($fileType, 1);
        } elseif ('.js' == $fileType) {
            $aceMode = 'javascript';
        } elseif ('.md' == $fileType) {
            $aceMode = 'markdown';
        }
        
        return $aceMode;
    }
}

