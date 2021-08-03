<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    //////////////Get data Belongs To
    //////////
    /////
    public function pengirim(){
        return $this->belongsTo('App\Models\User', 'pengirim_id', 'id');
    }

    public function penerima(){
        return $this->belongsTo('App\Models\User', 'penerima_id', 'id');
    }

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
