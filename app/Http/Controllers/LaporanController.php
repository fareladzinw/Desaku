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

class LaporanController extends Controller
{
    ////Show Laporan With Filter
    public function showLaporanFilter(Request $request){
        $id = $request->input('id');
        $pengirim_id = $request->input('pengirim_id');
        $penerima_id = $request->input('penerima_id');
        $judul = $request->input('judul');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');
        $status_selesai = $request->input('status_selesai');


        $created_at = $request->input('created_at');
        $created_at_from = $request->input('created_at_from');
        $created_at_to = $request->input('created_at_to');

        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;

        $data = Laporan::query()
        ->when(Input::has('id'), function ($query) use ($id) {
            $query->where('id',$id);
        })
        ->when(Input::has('pengirim_id'), function ($query) use ($pengirim_id) {
            $query->where('pengirim_id',$pengirim_id);
        })
        ->when(Input::has('penerima_id'), function ($query) use ($penerima_id) {
            $query->where('penerima_id',$penerima_id);
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
        ->when(Input::has('status_selesai'), function ($query) use ($status_selesai) {
            $query->where('status_selesai',$status_selesai);
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
            $res['detail'] = "Berhasil mendapatkan data Laporan";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Laporan";
            return response($res,406);
        }
    }



    //Show ALL Laporan
    public function showLaporan(){
        $data = Laporan::with('rt')
        ->with('rw')
        ->with('desa')
        ->get();

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Laporan";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Laporan";
            return response($res,406);
        }
    }


    //Change Status Penyelesaian Laporan
    public function validasiLaporan(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'status_penyelesaian' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');
        $status_penyelesaian = $request->input('status_penyelesaian');

        //Check Data
        $checkData = Laporan::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Laporan dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $idUserLogin = Auth()->user()->id;
        $checkTujuanLaporan = Laporan::where('id',$id)
        ->where('penerima_id',$idUserLogin)
        ->first();
        if(empty($checkTujuanLaporan)){
            $res['message'] = "failed";
            $res['detail'] = "Laporan Bukan Ditujukan Untuk Anda";
            return response($res,406);
        }

        //Edit Data
        $data = Laporan::find($id);
        $data->status_penyelesaian = $status_penyelesaian;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Status Laporan";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Status Laporan";
            return response($res,406);
        }
    }


    public function createLaporan(Request $request){
        $this->validate($request,[
            'pengirim_id' => 'required',
            'penerima_id' => 'required',
            'judul' => 'required',
            'detail_laporan' => 'required',
            'rt_id' => 'nullable',
            'rw_id' => 'nullable',
            'desa_id' => 'nullable',
        ]);

        $pengirim_id = $request->input('pengirim_id');
        $penerima_id = $request->input('penerima_id');
        $judul = $request->input('judul');
        $detail_laporan = $request->input('detail_laporan');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');

        $data = new Laporan;
        $data->pengirim_id = $pengirim_id;
        $data->penerima_id = $penerima_id;
        $data->judul = $judul;
        $data->detail_laporan = $detail_laporan;
        $data->status_penyelesaian = 0;
        $data->desa_id = $desa_id;
        $data->rw_id = $rw_id;
        $data->rt_id = $rt_id;

        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Membuat Laporan";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Membuat Laporan";
            return response($res,406);
        }
    }
}
