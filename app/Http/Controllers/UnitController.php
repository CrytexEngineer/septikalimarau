<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function json()
    {
        return DataTables::of(Unit::all())->addColumn('action', function ($row) {
            $action = '<div class="float-left"><a href="/unit/' . $row->id . '/edit" class="btn btn-primary "><i class="fas fa-edit"></i> Edit</a>';
            $action .= \Form::open(['url' => 'unit/' . $row->id, 'method' => 'delete', 'style' => 'float:right']);
            $action .= "<button type='submit'class='btn btn-primary '><i class='fas fa-trash-alt'></i>Hapus</button>";
            $action .= \Form::close();
            $action .= "</div>";
            return $action;
        })->make(true);

    }

    public function index()
    {
        return view('unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messege = ["unit_name.required" => "Harap Masukan Nama Unit"];
        $request->validate(["unit_name" => 'required', 'string', 'max:255'],$messege);
        $unit = new Unit($request->all());
        $unit->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['unit'] = Unit::where('id', '=', $id)->get()->first();
        return view("unit.edit", $data);
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
        $messege = ["unit_name.required" => "Harap Masukan Nama Unit"];
        $request->validate(["unit_name" => 'required', 'string', 'max:255'],$messege);
        $unit=Unit::where("id","=","$id");
        $unit->update(["unit_name"=>$request->input("unit_name")]);
        return redirect("unit");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::table('units')->where('id', $id)->delete();
        return redirect()->back();
    }
}
