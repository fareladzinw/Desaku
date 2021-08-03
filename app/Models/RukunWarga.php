<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RukunWarga extends Model
{
    use HasFactory;

    //////////////Get data Belongs To
    //////////
    /////
    public function desa(){
        return $this->belongsTo('App\Models\Desa', 'desa_id', 'id');
    }
}
