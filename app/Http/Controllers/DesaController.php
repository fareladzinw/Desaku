<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesaController extends Controller
{
    //

    public function userProfile() {
        return response()->json(auth()->user());
    }
}
