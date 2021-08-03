<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Desa;
use App\Models\RukunWarga;
use App\Models\RukunTetangga;
use App\Models\Surat;
use App\Models\Laporan;
use App\Models\Kegiatan;
use App\Models\Berita;
use Carbon\Carbon;

class BeritaController extends Controller
{
    //Show Berita With Filter
    public function showBeritaFilter(Request $request){
        $id = $request->input('id');
        $judul = $request->input('judul');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');

        $created_at = $request->input('created_at');
        $created_at_from = $request->input('created_at_from');
        $created_at_to = $request->input('created_at_to');

        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;

        $data = Berita::query()
        ->when(Input::has('id'), function ($query) use ($id) {
            $query->where('id',$id);
        })
        ->when(Input::has('judul'), function ($query) use ($judul) {
            $query->where('judul','like','%'.$judul.'%');
        })
        ->when(Input::has('rt_id'), function ($query) use ($rt_id) {
            $query->where('rt_id',$rt_id);
        })
        ->when(Input::has('rw_id'), function ($query) use ($rw_id) {
            $query->where('rw_id',$rw_id);
        })
        ->when(Input::has('desa_id'), function ($query) use ($desa_id) {
            $query->where('desa_id',$desa_id);
        })
        ->when(Input::has('created_at'), function ($query) use ($created_at) {
            $query->whereDate('created_at', '=', $created_at);
        })
        ->when(Input::has('created_at_from') && Input::has('created_at_to'), 
            function ($query) use ($created_at_from,$created_at_to) {
            $query->whereDate('created_at', '>=', $created_at_from);
            $query->whereDate('created_at', '<=', $created_at_to);
        })
        ->when(Input::has('skip'), function ($query) use ($skip) {
            $query->skip($skip);
        })
        ->when(Input::has('take'), function ($query) use ($take) {
            $query->take($take);
        })
        ->with('rt')
        ->with('rw')
        ->with('desa')
        ->get();


        if($data){
            $res['message'] = "success";
            $res['detail'] = "Berhasil mendapatkan data Berita";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Berita";
            return response($res,406);
        }
    }



    //Show ALL Berita
    public function showBerita(){
        $data = Berita::with('rt')
        ->with('rw')
        ->with('desa')
        ->get();;

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Berita";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Berita";
            return response($res,406);
        }
    }
}
