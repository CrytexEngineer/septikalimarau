<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param \App\Models\User $model
     * @return \Illuminate\View\View
     */

    private $user;

    public function json(Request $request)
    {
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $this->user = DB::table('users')->leftJoin("roles", 'users.role_id', "=", "roles.id")
                ->leftJoin('units', 'units.id', '=', 'users.unit_id');
        } else if (Auth::user()->hasAnyRoles(['Kanit'])) {
            $this->user = DB::table('users')->leftJoin("roles", 'users.role_id', "=", "roles.id")
                ->leftJoin('units', 'units.id', '=', 'users.unit_id')->where('units.id', '=', Auth::user()->unit_id);
        } else if (Auth::user()->hasAnyRoles(['Petugas'])) {
            $this->user = DB::table('users')->leftJoin("roles", 'users.role_id', "=", "roles.id")
                ->leftJoin('units', 'units.id', '=', 'users.unit_id')->where('users.id', '=', Auth::user()->id);
        }

        return DataTables::of($this->user)->addColumn('action', function ($row) {

            $action = '<div class="float-sm-left"><a href="/user/' . $row->nip . '/edit" class="btn btn-primary "><i class="fas fa-user-edit"></i> Ubah</a>';
//            $action .= '<div class="float-sm-right"><a href="/task/' . $row->id . '/dashboard" class="btn btn-primary "><i class="fas fa-edit"></i> Edit</a>';
            $action .= \Form::open(['url' => 'user/' . $row->nip, 'method' => 'delete', 'style' => 'float:right']);

            if (!Auth::user()->hasAnyRoles(['Petugas'])) {
                $action .= "<button type='submit'class='btn btn-primary '><i class='fas fa-trash-alt'></i>Hapus</button>";
            }
            $action .= \Form::close();
            $action .= "</div>";
            return $action;
        })->make(true);
    }

    public function index(User $model)
    {
        return view('users.index');
    }

    public function create()
    {
        $data['roles'] = Role::all();
        $data['units'] = Unit::pluck('unit_name', 'id');
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['units'] = Unit::pluck('unit_name', 'id');
        } else {
            $data['units'] = Unit::where('units.id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
        }
        return view('users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messege = ["nip.required" => "Harap Masukan NIP",
            "name.required" => "Harap Masukan Nama",
            "email.required" => "Harap Masukan Email",
            "email.email" => "Email Tidak Sesuai",
            "password.required" => "Harap Masukan Password",
            "unit_id.required" => "Harap Masukan Unit",
            "role_id.required" => "Harap Pilih Role "];
        $request->validate(["name" => 'required', 'string', 'max:255',
            "nip" => ['required', 'integer', 'unique:users'],
            "email" => ['required', 'string', 'email', 'unique:users'],
            "unit_id" => ['required', 'string'],
            "password" => ['required', 'string', 'password'],
            "role_id" => 'required', 'integer', $messege]);

        $user = new User($request->except(['_method', '_token', 'password']));
        $user['password'] = Hash::make('password');
        $user->save();
        return redirect()->to("/user");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['task'] = Task::where('id', '=', $id)->pluck('task_name', 'id');

        return view('item.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['roles'] = Role::all();
        $data['user'] = User::where("nip", "=", $id)->Join("roles", 'users.role_id', "=", "roles.id")->first();
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['units'] = Unit::pluck('unit_name', 'id');
        } else {
            $data['units'] = Unit::where('units.id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
        }

        return view('users.edit', $data);
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
        $messege = ["nip.required" => "Harap Masukan NIP",
            "name.required" => "Harap Masukan Nama",
            "email.required" => "Harap Masukan Email",
            "email.email" => "Email Tidak Sesuai",
            "password.required" => "Harap Masukan Password",
            "unit_id.required" => "Harap Masukan Unit",
            "role_id.required" => "Harap Pilih Role "];
        $request->validate(["name" => 'required', 'string', 'max:255',
            "nip" => 'required', 'integer',
            "email" => 'required', 'string', 'email',
            "unit_id" => 'required', 'string',
            "password" => 'required', 'string', 'password',
            "role_id" => 'required', 'integer'], $messege);
        $user = User::where('nip', $id);
        $user->update($request->except(['_method', '_token']));
        $user->update(['password' => Hash::make($request->input('password'))]);
        return redirect("user/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('nip', '=', $id)->get()->first()->delete();
        return redirect()->back();
    }
}
