<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WargaController extends Controller
{

    public function userProfile(){

        //Inisialisasi ID 
        $idUser = Auth()->user()->id;
        $idDesa = Auth()->user()->desa_id;
        $idRw = Auth()->user()->rw_id;
        $idRt = Auth()->user()->rt_id;

        $dataProfil = User::where('id',$idUser)
        ->get();

        $dataDesa = Desa::where('id',$idDesa)
        ->get();

        $dataRw = RukunWarga::where('id',$idRw)
        ->where('desa_id',$idDesa)
        ->get();

        $dataRt = RukunTetangga::where('id',$idRt)
        ->where('rw_id',$idRw)
        ->where('desa_id',$idDesa)
        ->get();

        $data = new StdClass();
        $data->dataDesa = $dataDesa;
        $data->dataRw = $dataRw;
        $data->dataRt = $dataRt;
        $data->dataProfil = $dataProfil;

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data User";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data User";
            return response($res,406);
        }
    }
}
