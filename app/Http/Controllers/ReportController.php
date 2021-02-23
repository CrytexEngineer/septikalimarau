<?php

namespace App\Http\Controllers;

use App\Export\ReportExporter;
use App\Exports\StudentBySubjectExport;
use App\Models\Images;
use App\Models\Report;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $reports;

    public function json(Request $request)
    {

        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $this->reports = DB::table('reports')->select('reports.*',
                'tasks.task_name',
                'units.unit_name',
                'users.name',
                DB::raw('COUNT(images.id) as jumlahGambar'))
                ->join('units', 'reports.unit_id', '=', 'units.id')
                ->join('tasks', 'reports.task_id', "=", "tasks.id")
                ->leftJoin('users', 'users.id', '=', 'reports.petugas_id')
                ->leftjoin('images', 'reports.id', '=', 'images.report_id')
                ->where("reports.status_id", "=", $request->input("status_id"))
                ->where('reports.unit_id', '=', Auth::user()->unit_id)
                ->groupBy('reports.id')
                ->orderBy('reports.updated_at', 'desc')->get()->all();

        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $this->reports = DB::table('reports')->select('reports.*',
                'tasks.task_name',
                'units.unit_name',
                'users.name',
                DB::raw('COUNT(images.report_id) as jumlahGambar'))
                ->leftJoin('units', 'reports.unit_id', '=', 'units.id')
                ->join('tasks', 'reports.task_id', "=", "tasks.id")
                ->leftJoin('users', 'users.id', '=', 'reports.petugas_id')
                ->leftJoin('images', 'reports.id', '=', 'images.report_id')
                ->where("reports.status_id", "=", $request->input("status_id"))
//                ->where('reports.unit_id', '=', $request->input("unit_id"))
                ->groupBy('reports.id')
                ->orderBy('reports.updated_at', 'desc')->get()->all();

        }


        foreach ($this->reports as $report) {
            $report->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->isoFormat('dddd, D MMMM Y/HH:mm');
            $report->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $report->updated_at)->isoFormat('dddd, D MMMM Y/HH:mm');
        }

        return DataTables::of($this->reports)->addColumn('action', function ($row) {

            $action = '<div class="float-sm-left"><a href="/report/' . $row->id . '" class="btn btn-primary" style="f"><i class="fas fa-list"></i> </a>';

            if ($row->status_id == 1 || $row->status_id == 6) {
                $action .= \Form::open(['url' => 'report/' . $row->id, 'method' => 'patch', 'style' => 'float:left margin_right:8px']);
                $action .= "<input type='hidden' id='status_id' name='status_id' value=2>";
                $action .= "<button type='submit'class='btn btn-primary btn-block'><i class='fas fa-key'></i></button>";
                $action .= \Form::close();
                $action .= \Form::open(['url' => 'report/' . $row->id, 'method' => 'delete', 'style' => 'float:right']);
                $action .= "<button onclick='return confirm(\"Apakah Anda Yakin?\")'  type='submit'class='btn btn-primary btn-block'><i class='fas fa-trash'></i></button>";
                $action .= \Form::close();
            }
            if ($row->status_id == 2) {
                if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {

                    $action .= \Form::open(['url' => 'report/' . $row->id, 'method' => 'patch', 'style' => 'float:left margin_right:8px']);
                    $action .= "<input type='hidden' id='status_id' name='status_id' value=5>";
                    $action .= "<button type='submit'class='btn btn-primary  btn-block'><i class='fas fa-check'></i> </button>";
                    $action .= \Form::close();

                    $action .= \Form::open(['url' => 'report/' . $row->id, 'method' => 'patch', 'style' => 'float:right']);
                    $action .= "<input type='hidden' id='status_id' name='status_id' value=6>";
                    $action .= "<button type='submit'class='btn btn-primary  btn-block'><i class='fas fa-times-circle'></i> </button>";
                    $action .= \Form::close();

                }
            }

            if ($row->status_id == 5) {
                $action .= '<div class="float-sm-right"><a href="/report/export/' . $row->id . '" class="btn btn-primary "><i class="fas fa-download"></i> </a>';


            }

            $action .= "</div>";
            return $action;
        })->make(true);

    }


    public function index()
    {
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Active";
            return view('report.index', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Active";
            return view('report.index', $data);
        }

    }

    public function archive()
    {
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Approved";
            return view('report.archive', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Approved";
            return view('report.archive', $data);
        }

    }

    public function reject()
    {
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Reject";
            return view('report.reject', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Reject";
            return view('report.reject', $data);
        }

    }

    public function review()
    {
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Submitted";
            return view('report.review', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Submitted";
            return view('report.review', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['unit'] = Unit::all();
        $data['task'] = Task::all();
        return view('report.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messeges = ['unit_id.required' => "Harap Pilih Unit", 'task_id.required' => "Harap Pilih Task"];

        $request->validate([
            'unit_id' => ['required', 'integer'],
            'task_id' => ['required', 'integer'],
        ], $messeges);

        $task = new Report(['status_id' => 1, 'task_id' => $request->input('task_id'), 'unit_id' => $request->input('unit_id')]);

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
        return redirect()->to("record/" . $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function mass_update(Request $request)
    {


        $reports = $request->input('reports');

        $counter = 0;
        foreach ($reports as $report) {


            $report['kasi_id'] = User::where('role_id', '=', '2')->get()->first()->id;
            if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
                $report['kanit_id'] = User::where('role_id', '=', '3')
                    ->where('unit_id', '=', Auth::user()->unit_id)->get()->first()->id;

                $report['petugas_id'] = User::where('id', '=', Auth::user()->id)->get()->first()->id;
            }

            if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
                if (!isset($report['petugas_id'])
                    || !isset($report['kanit_id'])
                    || !isset($report['kasi_id'])) {


                    if ($request->input('status_id') == 1) {
                        return response()->json(['statusCode' => '201', 'messege' => "Eror Laporan dengan ID " . $report['id'] . " Belum Lengkap
                    dan " . $counter . " Laporan Berhasil Di Tolak"]);
                    }
                    if ($request->input('status_id') == 2) {
                        return response()->json(['statusCode' => '201', 'messege' => "Eror Laporan dengan ID " . $report['id'] . " Belum Lengkap
                    dan " . $counter . " Laporan Berhasil Di Submit"]);
                    }
                    if ($request->input('status_id') == 5) {
                        return response()->json(['statusCode' => '201', 'messege' => "Eror Laporan dengan ID " . $report['id'] . " Belum Lengkap
                    dan " . $counter . " Laporan Berhasil Di Arsip"]);
                    }
                    if ($request->input('status_id') == 6) {
                        return response()->json(['statusCode' => '201', 'messege' => "Eror Laporan dengan ID " . $report['id'] . " Belum Lengkap
                    dan " . $counter . " Laporan Berhasil Di Tolak"]);
                    }
                }
            }


            $report["status_id"] = $request->input('status_id');

            $updatedReport = Report::where('id', $report['id'])->first();

            $updatedReport->timestamps = false;
            $updatedReport->update($report);
            $counter++;
        }
        if ($request->input('status_id') == 1) {
            return response()->json(['statusCode' => '200', 'messege' => $counter . " Laporan Berhasil Di Tolak"]);
        }
        if ($request->input('status_id') == 2) {
            return response()->json(['statusCode' => '200', 'messege' => $counter . " Laporan Berhasil Di Submit"]);
        }
        if ($request->input('status_id') == 5) {
            return response()->json(['statusCode' => '200', 'messege' => $counter . " Laporan Berhasil Di Arsip"]);
        }
        if ($request->input('status_id') == 6) {
            return response()->json(['statusCode' => '200', 'messege' => $counter . " Laporan Berhasil Di Tolak"]);
        }
    }


    public function mass_delete(Request $request)
    {
        $counter = 0;
        $reports = $request->input('reports');
        foreach ($reports as $report) {
            Report::where('id', $report['id'])->delete();
            $counter++;
        }

        return response()->json(['statusCode' => '200', 'messege' => $counter . " Laporan Berhasil Di Hapus"]);
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

        $report = Report::where("id", "=", "$id")->first();


        if (!$report->petugas_id
            || !$report->kanit_id
            || !$report->kasi_id) {
            return redirect()->back()->withErrors('Pastikan data PETUGAS, KANIT, dan KASI telah diisi!');
        }
        $report->status_id = $request->input('status_id');
        $report->timestamps = false;

        $report->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $images = DB::table('images')->where('report_id', $id)->get();
        foreach ($images as $image) {
            File::delete('gambar_harian/' . $image->image_path);
            Images::where('id', $image->id)->delete();
        }
        DB::table('reports')->where('id', $id)->delete();
        return redirect()->back()->withInput();

    }

    public function saveExport($id)
    {
        $unit = Report::where('reports.id', $id)->join('units', 'reports.unit_id', '=', 'units.id')->get()->first()['unit_name'];
        $task = Report::where('reports.id', $id)->join('tasks', 'reports.task_id', '=', 'tasks.id')->get()->first()['task_name'];
        $date = date("y:m:d", strtotime(Report::where('id', $id)->get()->first()['created_at']));


        return Excel::download(new ReportExporter($id), $unit . '-' . $task . '-' . $date . '.xlsx');

    }
}
