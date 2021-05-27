<?php

namespace App\Http\Controllers;

use App\Export\MassReportExporter;
use App\Export\ReportExporter;
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
                'petugas_pagi.name as petugas_pagi',
                'petugas_siang.name as petugas_siang',
                DB::raw('COUNT(images.report_id) as jumlahGambar'))
                ->leftJoin('units', 'reports.unit_id', '=', 'units.id')
                ->join('tasks', 'reports.task_id', "=", "tasks.id")
                ->leftJoin('users as petugas_pagi', 'petugas_pagi.id', '=', 'reports.petugas_pagi_id')
                ->leftJoin('users as petugas_siang', 'petugas_siang.id', '=', 'reports.petugas_siang_id')
                ->leftjoin('images', 'reports.id', '=', 'images.report_id')
                ->where("reports.status_id", "=", $request->input("status_id"))
                ->where('reports.unit_id', '=', Auth::user()->unit_id);

            if ($request->filter_tanggal && $request->filter_tanggal != "ALL") {
                $this->reports = $this->reports->whereDate('reports.created_at', Carbon::createFromFormat('d-m-Y', $request->filter_tanggal)->isoFormat('Y-MM-D'));
            }


        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {

            $this->reports = DB::table('reports')->select('reports.*',
                'tasks.task_name',
                'units.unit_name',
                'petugas_pagi.name as petugas_pagi',
                'petugas_siang.name as petugas_siang',
                DB::raw('COUNT(images.report_id) as jumlahGambar'))
                ->leftJoin('units', 'reports.unit_id', '=', 'units.id')
                ->join('tasks', 'reports.task_id', "=", "tasks.id")
                ->leftJoin('users as petugas_pagi', 'petugas_pagi.id', '=', 'reports.petugas_pagi_id')
                ->leftJoin('users as petugas_siang', 'petugas_siang.id', '=', 'reports.petugas_siang_id')
                ->leftJoin('images', 'reports.id', '=', 'images.report_id')
                ->where("reports.status_id", "=", $request->input("status_id"));


            if ($request->filter_tanggal && $request->filter_tanggal != "ALL") {
                $this->reports = $this->reports->whereDate('reports.created_at', Carbon::createFromFormat('d-m-Y', $request->filter_tanggal)->isoFormat('Y-MM-D'));

            }

            if ($request->unit_id == 6 || !$request->unit_id) {
                $this->reports = $this->reports->groupBy('reports.id');

            } else {
                $this->reports = $this->reports
                    ->where('reports.unit_id', '=', $request->input("unit_id"));
            }


        }

        $this->reports = $this->reports->groupBy('reports.id')
            ->orderBy('reports.updated_at', 'desc')->get()->all();

        foreach ($this->reports as $report) {
            $report->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->isoFormat('dddd, D MMMM Y/HH:mm');
            $report->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $report->updated_at)->isoFormat('dddd, D MMMM Y/HH:mm');
        }


        return DataTables::of($this->reports)->addColumn('action', function ($row) {
            $action = '<a href="/report/' . $row->id . '" class="btn btn-primary btn-block" type="submit"><i class="fas fa-list"></i> </a>';
            if ($row->status_id == 5) {
                $action .= '<div><a href="/report/export/' . $row->id . '" class="btn btn-outline-primary btn-block "><i class="fas fa-download"></i> </a>';
                $action .= '</br>';

            }

            $action .= "</div>";
            $action .= '</div>';
            return $action;
        })->make(true);

    }


    public function index()
    {
        $creationdates = DB::table('reports')->select(DB::raw('DATE_FORMAT(reports.created_at,"%d-%m-%Y") as created_at'))->distinct()->orderBy('reports.created_at', 'desc')->limit(10)->pluck('created_at');
        $creationdates['ALL'] = 'ALL';

        foreach ($creationdates as $key => $value) {
            $creationdates[$key] = $value;
        }
        $rejectedReports = Report::where('status_id', 6);

        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Active";
            $data['created_at'] = $creationdates;
            $rejectedReports = $rejectedReports->where('unit_id', Auth::user()->unit_id)->get()->count();
            $data['rejectedReports'] = $rejectedReports;

            return view('report.index', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Active";
            $data['created_at'] = $creationdates;

            $rejectedReports = $rejectedReports->get()->count();
            $data['rejectedReports'] = $rejectedReports;

            return view('report.index', $data);
        }

    }

    public function archive()
    {
        $creationdates = DB::table('reports')->select(DB::raw('DATE_FORMAT(reports.created_at,"%d-%m-%Y") as created_at'))->distinct()->orderBy('reports.created_at', 'desc')->pluck('created_at');
//        $creationdates['ALL'] = 'ALL';
        foreach ($creationdates as $key => $value) {
            $creationdates[$key] = $value;
        }
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Approved";
            $data['created_at'] = $creationdates;
            return view('report.archive', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Approved";
            $data['created_at'] = $creationdates;
            return view('report.archive', $data);
        }

    }

    public function reject()
    {
        $creationdates = DB::table('reports')->select(DB::raw('DATE_FORMAT(reports.created_at,"%d-%m-%Y") as created_at'))->distinct()->orderBy('reports.created_at', 'desc')->limit(10)->pluck('created_at');
        $creationdates['ALL'] = 'ALL';
        foreach ($creationdates as $key => $value) {
            $creationdates[$key] = $value;
        }
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Rejected";
            $data['created_at'] = $creationdates;
            return view('report.reject', $data);
        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Rejected";
            $data['created_at'] = $creationdates;
            return view('report.reject', $data);
        }

    }

    public function review()
    {
        $creationdates = DB::table('reports')->select(DB::raw('DATE_FORMAT(reports.created_at,"%d-%m-%Y") as created_at'))->distinct()->orderBy('reports.created_at', 'desc')->limit(10)->pluck('created_at');
        $creationdates['ALL'] = 'ALL';
        foreach ($creationdates as $key => $value) {
            $creationdates[$key] = $value;
        }
        if (Auth::user()->hasAnyRoles(['Petugas', 'Kanit'])) {
            $data['unit'] = Unit::where('id', '=', Auth::user()->unit_id)->pluck('unit_name', 'id');
            $data['task'] = Task::where('tasks.unit_id', '=', Auth::user()->unit_id)->pluck('task_name', 'id');
            $data['status'] = "Submitted";
            $data['created_at'] = $creationdates;
            return view('report.review', $data);

        }
        if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
            $data['unit'] = Unit::all()->pluck('unit_name', 'id');
            $data['task'] = Task::all()->pluck('task_name', 'id');
            $data['status'] = "Submitted";
            $data['created_at'] = $creationdates;
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


        $existingReport = Report::where('unit_id', '=', $request->input('unit_id'))->
        where('task_id', '=', $request->input('task_id'))->
        whereDate('reports.created_at', Carbon::now()->toDateString())->get()->first();

        if (!$existingReport) {
            $report = new Report(['status_id' => 1, 'task_id' => $request->input('task_id'), 'unit_id' => $request->input('unit_id')]);
            $report->save();

            return redirect()->back();
        }

        $task = Task::where('id', $request->input('task_id'))->get()->first();
        return redirect()->back()->withErrors("Laporan untuk task " . $task->task_name . " telah dibuat hari ini,  Silahkan lanjutkan task yang dibuat petugas sebelumnya! (Silahkan Cek Laporan Ditinjau Jika Tidak Terlihat)");
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
                if (!isset($report['petugas_pagi_id'])) {
                    $report['petugas_pagi_id'] = User::where('id', '=', Auth::user()->id)->get()->first()->id;
                }
                if (!isset($report['petugas_siang_id'])) {
                    $report['petugas_siang_id'] = User::where('id', '=', Auth::user()->id)->get()->first()->id;
                }
            }

            if (Auth::user()->hasAnyRoles(['Admin', 'Kasi'])) {
                if (!isset($report['petugas_pagi_id'])
                    || !isset($report['petugas_siang_id'])
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


        if (!$report->petugas_pagi_id
            || !$report->petugas_siang_id
            || !$report->kanit_id
            || !$report->kasi_id) {
            return redirect()->back()->withInput($request->input())->withErrors('Pastikan data PETUGAS, KANIT, dan KASI telah diisi!');
        }
        $report->status_id = $request->input('status_id');
        $report->timestamps = false;

        $report->save();
        return redirect()->back()->withInput($request->input());
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

    public function mass_export(Request $request)
    {

        $unit = Report::where('reports.id', $request->filter_unit_unduh)->join('units', 'reports.unit_id', '=', 'units.id')->get()->first()['unit_name'];
        $reports = Report::where('reports.unit_id', '=', $request->filter_unit_unduh)
            ->whereBetween('reports.created_at', [Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai)->isoFormat('Y-MM-D'), Carbon::createFromFormat('d-m-Y', $request->tanggal_selesai)->isoFormat('Y-MM-D')])
            ->where('reports.status_id', '=', 5)->get()->all();

        if (!$reports) {

            return redirect()->back()->withErrors('Data Tidak Tersedia');
        }
        return Excel::download(new MassReportExporter($reports), $unit . '-' . $request->tanggal_mulai . '-' . $request->tanggal_selesai . '.xlsx');


    }
}
