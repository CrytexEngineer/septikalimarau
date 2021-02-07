<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable=['report_id','item_id','kondisi_pagi','kondisi_siang'];
    use HasFactory;
}
