<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Task;
use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function json(Request $request){

        return DataTables::of(Item::where('task_id','=',$request->input('id'))->get()->all())->addColumn('action', function ($row) {
            $action = '<div class="float-left"><a href="/item/' . $row->id . '/dashboard" class="btn btn-primary "><i class="fas fa-edit"></i> Edit</a>';
            $action .= \Form::open(['url' => 'item/' . $row->id, 'method' => 'delete', 'style' => 'float:right']);
            $action .= "<button type='submit'class='btn btn-primary '><i class='fas fa-trash-alt'></i>Hapus</button>";
            $action .= \Form::close();
            $action .= "</div>";
            return $action;
        })->make(true);
    }

    public function index($id)
    {
        return view('item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item=new Item($request->all());
        $item->save();
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $data['task']=Task::where('id','=',$id)->pluck('task_name','id');

      return view('item.index',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Item::where('id','=',$id)->get()->first()->delete();
        return redirect()->back();
    }
}
