<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    public function upload(){
        $data['gambar']=Images::all();
        return view('upload.upload',$data);
    }

    public function proses_upload(Request $request){
        $this->validate($request, [
            'file' => 'required',
            'keterangan' => 'required',
        ]);

        // menyimpan data file yang diupload ke variabel $file
        $file = $request->file('file');
        $nama_file = time()."_".$file->getClientOriginalName();

        Images::create(["image_path"=>$nama_file,"keterangan"=>$request->get('keterangan')]);

        // nama file
        echo 'File Name: '.$file->getClientOriginalName();
        echo '<br>';

        // ekstensi file
        echo 'File Extension: '.$file->getClientOriginalExtension();
        echo '<br>';

        // real path
        echo 'File Real Path: '.$file->getRealPath();
        echo '<br>';

        // ukuran file
        echo 'File Size: '.$file->getSize();
        echo '<br>';

        // tipe mime
        echo 'File Mime Type: '.$file->getMimeType();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'gambar_harian';

        // upload file
        $file->move($tujuan_upload,$nama_file);
    }

    public function hapus($id){
        // hapus file
        $gambar = Images::where('id',$id)->first();
        dd($gambar);
        File::delete('gambar_harian/'.$gambar->image_path);

        // hapus data
        Images::where('id',$id)->delete();

        return redirect()->back();
    }
}
