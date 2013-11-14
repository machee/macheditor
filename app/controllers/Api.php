<?php

class Api extends BaseController
{
    /**
     * @var ProjectRepository
     */
    private $projects;
    
    public function __construct(ProjectRepository $projects)
    {
        $this->projects = $projects;
    }
    
    public function update($projectName, $filePath)
    {
        return $this->fileAction($projectName, $filePath, function ($file) {
            return $file->setContents(Input::get('content'));
        });
    }
    
    public function delete($projectName, $filePath)
    {
        return $this->fileAction($projectName, $filePath, function ($file) {
            return $file->delete();
        });
    }
    
    protected function fileAction($projectName, $filePath, $function)
    {
        if ( ! ($project = $this->projects->find($projectName)) ) {
            return Response::json(['message' => 'project not found'], 404);
        }
        $response = $function($project->findFile($filePath));
        
        return Response::json($response, $response['error'] ? 500 : 200);
    }
}

