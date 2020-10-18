<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Http\Resources\Project as ProjectResource;

class ProjectsController extends Controller
{
    public function index(){
        $projects = Project::orderBy('created_at', 'desc')->paginate(5);

        return ProjectResource::collection($projects);
    }

    public function store(Request $request){
        $project = $request->isMethod('put') ? Project::findOrFail($request->name) : new Project;

        $project->name = $request->input('name');
        $project->desc = $request->input('desc');

        if($project->save()){
            return new ProjectResource($project);
        }
    }

    public function destroy($id){
        // Get Project
        $project = Project::findOrFail($id);

        if($project->delete()){
            return new ProjectResource($project);
        }
    }
}
