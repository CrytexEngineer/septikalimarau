<?php

namespace App\Http\Controllers;

use App\Major;
use App\Models\Task;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function json(Request $request)
    {

        return DataTables::of(Task::where('unit_id','=',$request->input('id')))->addColumn('action', function ($row) {

            $action = '<div class="float-sm-left"><a href="/task/' . $row->id .'" class="btn btn-primary "><i class="fas fa-list"></i> Item</a>';
//            $action .= '<div class="float-sm-right"><a href="/task/' . $row->id . '/dashboard" class="btn btn-primary "><i class="fas fa-edit"></i> Edit</a>';
            $action .= \Form::open(['url' => 'task/' . $row->id, 'method' => 'delete', 'style' => 'float:right']);
            $action .= "<button type='submit'class='btn btn-primary '><i class='fas fa-trash-alt'></i>Hapus</button>";
            $action .= \Form::close();
            $action .= "</div>";
            return $action;
        })->make(true);
    }

    public function index()
    {

        if (Auth::user()->hasAnyroles(['Kanit'])) {
            $data['unit'] = Unit::where('id',Auth::user()->unit_id)->pluck('unit_name', 'id');
            return view("task.index",$data);
        }

        if (Auth::user()->hasAnyroles(['Admin','Kasi'])) {
            $data['unit'] = Unit::pluck('unit_name', 'id');
            return view("task.index",$data);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messege = ["task_name.required" => "Harap Masukan Nama Task"];
        $request->validate(["task_name" => 'required', 'string', 'max:255'],$messege);
        $task = new Task($request->all());
        $task->save();
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return redirect('/item/'.$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('task.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::table('tasks')->where('id', $id)->delete();
        return redirect()->back()->withInput();
    }
}
