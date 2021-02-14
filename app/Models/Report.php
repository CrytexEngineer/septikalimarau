<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
protected $fillable =['status_id','task_id','unit_id','keterangan','petugas_id','kanit_id','kasi_id'];



//    public function getCreatedAtAttribute($timestamp) {
//        Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->isoFormat('dddd, D MMMM Y/hh:mm');
//    }
}
