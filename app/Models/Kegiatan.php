<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    public function rt(){
        return $this->belongsTo('App\Models\RukunTetangga', 'rt_id', 'id');
    }

    public function rw(){
        return $this->belongsTo('App\Models\RukunWarga', 'rw_id', 'id');
    }

    public function desa(){
        return $this->belongsTo('App\Models\Desa', 'desa_id', 'id');
    }
}
