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

class SuratController extends Controller
{
    ////Show Surat With Filter
    public function showSuratFilter(Request $request){
        $id = $request->input('id');
        $pengirim_id = $request->input('pengirim_id');
        $penerima_id = $request->input('penerima_id');
        $keperluan = $request->input('keperluan');
        $status = $request->input('status');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');

        $created_at = $request->input('created_at');
        $created_at_from = $request->input('created_at_from');
        $created_at_to = $request->input('created_at_to');

        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;

        $data = Surat::query()
        ->when(Input::has('id'), function ($query) use ($id) {
            $query->where('id',$id);
        })
        ->when(Input::has('pengirim_id'), function ($query) use ($pengirim_id) {
            $query->where('pengirim_id',$pengirim_id);
        })
        ->when(Input::has('penerima_id'), function ($query) use ($penerima_id) {
            $query->where('penerima_id',$penerima_id);
        })
        ->when(Input::has('keperluan'), function ($query) use ($keperluan) {
            $query->where('keperluan','like','%'.$keperluan.'%');
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
        ->when(Input::has('status'), function ($query) use ($status) {
            $query->where('status',$status);
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
            $res['detail'] = "Berhasil mendapatkan data Surat";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Surat";
            return response($res,406);
        }
    }




    //Show ALL Surat
    public function showSurat(){
        $data = Surat::with('rt')
        ->with('rw')
        ->with('desa')
        ->get();

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Surat";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Surat";
            return response($res,406);
        }
    }




    //Change Status Surat
    public function changeStatusSurat(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'status' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');
        $status = $request->input('status');

        //Check Data
        $checkData = Surat::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Surat dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $idUserLogin = Auth()->user()->id;
        $checkTujuanSurat = Surat::where('id',$id)
        ->where('penerima_id',$idUserLogin)
        ->first();
        if(empty($checkTujuanSurat)){
            $res['message'] = "failed";
            $res['detail'] = "Surat Bukan Ditujukan Untuk Anda";
            return response($res,406);
        }

        //Edit Data
        $data = Surat::find($id);
        $data->status = $status;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Status Surat";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Status Surat";
            return response($res,406);
        }
    }



    public function createSurat(Request $request){
        $this->validate($request,[
            'pengirim_id' => 'required',
            'penerima_id' => 'required',
            'keperluan' => 'required',
            'file' => 'required',
            'rt_id' => 'nullable',
            'rw_id' => 'nullable',
            'desa_id' => 'nullable',
        ]);

        $pengirim_id = $request->input('pengirim_id');
        $penerima_id = $request->input('penerima_id');
        $keperluan = $request->input('keperluan');
        $file = $request->input('file');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');

        $data = new Surat;
        $data->pengirim_id = $pengirim_id;
        $data->penerima_id = $penerima_id;
        $data->keperluan = $keperluan;
        $data->status = 2;
        $data->desa_id = $desa_id;
        $data->rw_id = $rw_id;
        $data->rt_id = $rt_id;

        $name = $file->getClientOriginalName();
        $file->move(public_path('file/surat'), $name);
        $data->file = $name;

        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Membuat Surat";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Membuat Surat";
            return response($res,406);
        }
    }
}
