<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (\Auth::check()) {
        $user = \Auth::user();
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'user' => $user,
            'tasks' => $tasks,
        ];
        
        $data += $this->counts($user);
        return view('tasks.index', $data);
            
    
    }else {
        return view('welcome');
    }
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', [
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:10',
            ]);
        
        $task = new Task;
        $task ->content = $request->content;
        $task ->user_id = \Auth::user()->id;
        $task ->status = $request->status;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks();
            $task = Task::find($id);
            if ($user->id != $task->user_id) {
                return redirect('/');
            }
        $data += $this->counts($user);
        return view('tasks.show', ['task' => $task]);
        }else {
            return view('welcome');
        }
    }
        
    //     $task = Task::find($id);
        
    //     return view('tasks.show', [
    //         'task' => $task,
    //         ]);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks();
            $task = Task::find($id);
            if ($user->id != $task->user_id) {
                return redirect('/');
            }
        $data += $this->counts($user);
        return view('tasks.edit', ['task' => $task]);
        }else {
            return view('welcome');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'content' => 'required|max:191',
            'status' => 'required|max:10',
            ]);
            
        $task = Task::find($id);
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect('/');
    }
}
