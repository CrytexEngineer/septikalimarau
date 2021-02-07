<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
protected $fillable =['status_id','task_id','unit_id','keterangan','petugas_id','kanit_id','kasi_id'];
}
