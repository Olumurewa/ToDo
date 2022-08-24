<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->User_ID;
        $task = Task::find($user);
        if (!empty($task)){
            return response()->json([
                $task
            ], 201);
        }else{
            return response()->json([
                'message'=>'User has no tasks'
            ], 404);
        }
        
    }
    /**
     * 
     * Function to display all tasks
     */
    public function getAll(){
        $task = Task::all();
        return response()->json([
            $task
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [

            'name' => 'string|required',
            'User_ID' => 'integer|required',
            'description' => 'string|required',
            'due' => 'string|required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }
        $task = new Task;
        $task->name = $request->name;
        $task->User_ID = $request->User_ID;
        $task->description = $request->description;
        $task->due = $request->due;
        $task->completed = 0;
        
        if($task->save()){
            return response()->json([
                'Message'=>'Task Created'
            ], 201);
        }else {
            return response()->json([
                'error'=>'Invalid input parameters'
            ], 500);
        }

    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {   
        $user = $request->User_ID;
        $mytask = Task::find($user);


        if(!empty($mytask)){
            $task = Task::find($id);

            if (!empty($task)){
                return response()->json($task);
            }else{
                return response() -> json([
                    'message'=>'Task not found.' 
                ],404);
            }

        }else{
            return response() -> json([
                'message'=>'Task not found.' 
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'string',
            'User_ID' => 'integer',
            'description' => 'string',
            'due' => 'string',

        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }


        $user = $request->User_ID;
        $mytask = Task::find($user);
        if (!empty($mytask)){
            if(Task::where('id',$id)->exists()){
                $task = Task::find($id);
                $task->update($request->all());
                $task->save();
                return response()->json([
                    'message' => 'Task updated'
                ], 201);
            } else {
                return response()->json([
                    'message'=>'Task not found'
                ], 404);
            }
        }else{
            return response()->json([
                'message'=>'User has no tasks'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'string|required',
            'User_ID' => 'integer|required',
            'description' => 'string|required',
            'due' => 'string|required',

        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }


        $user = $request->User_ID;
        $mytask = Task::find($user);
        if (!empty($mytask)){
            if(Task::where('id',$id)->exists()){
                $task = Task::find($id);
                $task->update($request->all());
                $task->save();
                return response()->json([
                    'message' => 'Task updated'
                ], 201);
            } else {
                return response()->json([
                    'message'=>'Task not found'
                ], 404);
            }
        }else{
            return response()->json([
                'message'=>'User has no tasks'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Task::where('id', $id)->exists()){
            $route = Task::find($id);
            $route->delete();

            return response()->json([
                'message'=>'Task Deleted'
            ], 202);
        }
    }
}
