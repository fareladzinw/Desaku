<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RukunTetangga extends Model
{
    use HasFactory;

    //////////////Get data Belongs To
    //////////
    /////
    public function rw(){
        return $this->belongsTo('App\Models\RukunWarga', 'rw_id', 'id');
    }

    public function desa(){
        return $this->belongsTo('App\Models\Desa', 'desa_id', 'id');
    }
}
