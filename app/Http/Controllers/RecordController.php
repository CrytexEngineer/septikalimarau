<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Item;
use App\Models\Record;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param array $data
     * @return void
     */
    public function index(Request $request)
    {


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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


        foreach ($request->input('report_id') as $key => $report_id) {

            if ($request->input('report_id')) {
                $record = Record::updateOrCreate(
                    ['report_id' => $report_id, 'item_id' => $request->input('item_id')[$key]],
                    ['kondisi_siang' => $request->input('kondisi_siang')[$key], 'kondisi_pagi' => $request->input('kondisi_pagi')[$key]]
                );

                $report = Report::updateOrCreate(
                    ['id' => $report_id,], ['keterangan' => $request->input('keterangan'),
                        'kasi_id' => $request->input('kasi_id'),
                        'kanit_id' => $request->input('kanit_id'),
                        'petugas_id' => Auth::user()->id,
                        'updated_at' => Carbon::now()]
                );
            }
        }


        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = Report::where('id', '=', $id)->get();
        $data['report'] = $report;
        $data['records'] = Record::where('report_id', '=', $id)->join("items", "records.item_id", "=", "items.id")->get()->all();
        $data['selectedKasi'] = Report::where('reports.id', '=', $id)->join("users", "reports.kasi_id", "=", "users.id")->get()->first();
        $data['selectedKanit'] = Report::where('reports.id', '=', $id)->join("users", "reports.kanit_id", "=", "users.id")->get()->first();
        $data['selectedPetugas'] = Report::where('reports.id', '=', $id)->join("users", "reports.petugas_id", "=", "users.id")->get()->first();
        $data['kasi'] = User::where('role_id', '=', '2')->get()->pluck('name', 'id');
        $data['kanit'] = User::where('role_id', '=', '3')->get()->pluck('name', 'id');
        $data['petugas'] = User::where('id', '=', Auth::user()->id)->get()->pluck('name', 'id');
        $data['items'] = Item::where('task_id', '=', $report->first()->task_id)->get();
        $data['task'] = Task::where('tasks.id', '=', $report->first()->task_id);
        $data['gambar'] = Images::where("report_id", "=", $id)->get();
        $data['status'] = Report::where('reports.id', '=', $id)->join('status', 'status.id', '=', 'reports.status_id')->get()->first()['status_name'];

        if ($report->first()->status_id == 1) {
            return view("record.create", $data);
        } else {
            return view("record.view", $data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        //
    }


    public function proses_upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
            'keterangan' => 'required',
        ]);

        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        Images::create(["report_id" => $request->get('report_id'), "image_path" => $nama_file, "keterangan" => $request->get('keterangan')]);

        // nama file
        echo 'File Name: ' . $file->getClientOriginalName();
        echo '<br>';

        // ekstensi file
        echo 'File Extension: ' . $file->getClientOriginalExtension();
        echo '<br>';

        // real path
        echo 'File Real Path: ' . $file->getRealPath();
        echo '<br>';

        // ukuran file
        echo 'File Size: ' . $file->getSize();
        echo '<br>';

        // tipe mime
        echo 'File Mime Type: ' . $file->getMimeType();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'gambar_harian';

        // upload file
        $file->move($tujuan_upload, $nama_file);
        return redirect()->back()->withInput();
    }

    //hapus gambar
    public function hapus($id)
    {

        // hapus file
        $gambar = Images::where('id', $id)->first();
        File::delete('gambar_harian/' . $gambar->image_path);

        // hapus data
        Images::where('id', $id)->delete();

        return redirect()->back();
    }
}
