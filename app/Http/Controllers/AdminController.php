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


class AdminController extends Controller
{

    












    // API CRUD DESA
    public function createDesa(Request $request){
        $this->validate($request,[
            'nama' => 'required'
        ]);

        //Request Input
        $nama = $request->input('nama');

        //Check Data
        $checkNama = Desa::where('nama',$nama)->first();
        if(!empty($checkNama)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan nama tersebut sudah terdaftar";
            return response($res,406);
        }

        //Input Data
        $data = new Desa;
        $data->nama = $nama;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menambahkan Data Desa";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal menambahkan Data Desa";
            return response($res,406);
        }
    }

    public function updateDesa(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'nama' => 'required'
        ]);

        //Request Input
        $id = $request->input('id');
        $nama = $request->input('nama');

        //Check Data
        $checkData = Desa::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkNama = Desa::where('nama',$nama)->first();
        if(!empty($checkNama)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan nama tersebut sudah terdaftar";
            return response($res,406);
        }

        //Edit Data
        $data = Desa::find($id);
        $data->nama = $nama;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Data Desa";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Data Desa";
            return response($res,406);
        }
    }

    public function deleteDesa(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');

        //Check Data
        $checkData = Desa::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkRw = RukunWarga::where('desa_id',$id)->first();
        if(!empty($checkRw)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan Id tersebut Tidak dapat dihapus, karena Terdapat Data RW pada Desa Tersebut";
            return response($res,406);
        }

        //find Data
        $data = Desa::find($id);

        //Delete Data
        if($data -> delete()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menghapus Data Desa";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Menghapus Data Desa";
            return response($res,406);
        }
    }


    public function showDesa(){
        $data = Desa::get();

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Desa";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Desa";
            return response($res,406);
        }
    }

    //================================================================













    // API CRUD RW
    public function createRw(Request $request){
        $this->validate($request,[
            'nama' => 'required',
            'desa_id' => 'required'
        ]);

        //Request Input
        $nama = $request->input('nama');
        $desa_id = $request->input('desa_id');


        //Check Data
        $checkDesa = Desa::where('id',$desa_id)->first();
        if(empty($checkDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        //Input Data
        $data = new RukunWarga;
        $data->nama = $nama;
        $data->desa_id = $desa_id;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menambahkan Data RW";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal menambahkan Data RW";
            return response($res,406);
        }
    }

    public function updateRw(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'nama' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');
        $nama = $request->input('nama');

        //Check Data
        $checkData = RukunWarga::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Warga dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        //Edit Data
        $data = RukunWarga::find($id);
        $data->nama = $nama;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Data Rukun Warga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Data Rukun Warga";
            return response($res,406);
        }
    }

    public function deleteRw(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');

        //Check Data
        $checkData = RukunWarga::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Warga dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkRt = RukunTetangga::where('rw_id',$id)->first();
        if(!empty($checkRt)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Warga dengan Id tersebut Tidak dapat dihapus, karena Terdapat Data Rt pada RW Tersebut";
            return response($res,406);
        }

        //find Data
        $data = RukunWarga::find($id);

        //Delete Data
        if($data -> delete()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menghapus Data Rukun Warga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Menghapus Data Rukun Warga";
            return response($res,406);
        }
    }


    public function showRw(){
        $data = RukunWarga::get();

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Rukun Warga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Rukun Warga";
            return response($res,406);
        }
    }
    //================================================================














    // API CRUD RT
    public function createRt(Request $request){
        $this->validate($request,[
            'nama' => 'required',
            'desa_id' => 'required',
            'rw_id' => 'required',
        ]);

        //Request Input
        $nama = $request->input('nama');
        $desa_id = $request->input('desa_id');
        $rw_id = $request->input('rw_id');


        //Check Data
        $checkDesa = Desa::where('id',$desa_id)->first();
        if(empty($checkDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Desa dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkRW = RukunWarga::where('id',$rw_id)->first();
        if(empty($checkRW)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Warga dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkRelasi = RukunWarga::where('id',$rw_id)
        ->where('desa_id',$desa_id)
        ->first();
        if(empty($checkRelasi)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Warga dengan Id tersebut Tidak Terdapat pada Id Desa Tersebut";
            return response($res,406);
        }


        //Input Data
        $data = new RukunTetangga;
        $data->nama = $nama;
        $data->desa_id = $desa_id;
        $data->rw_id = $rw_id;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menambahkan Data Rukun Tetangga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal menambahkan Data Rukun Tetangga";
            return response($res,406);
        }
    }

    public function updateRt(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'nama' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');
        $nama = $request->input('nama');

        //Check Data
        $checkData = RukunTetangga::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Tetangga dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        //Edit Data
        $data = RukunTetangga::find($id);
        $data->nama = $nama;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Data Rukun Tetangga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Data Rukun Tetangga";
            return response($res,406);
        }
    }

    public function deleteRt(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');

        //Check Data
        $checkData = RukunTetangga::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "Rukun Tetangga dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        //find Data
        $data = RukunTetangga::find($id);

        //Delete Data
        if($data -> delete()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menghapus Data Rukun Tetangga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Menghapus Data Rukun Tetangga";
            return response($res,406);
        }
    }


    public function showRt(){
        $data = RukunTetangga::get();

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua Rukun Tetangga";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data Rukun Tetangga";
            return response($res,406);
        }
    }
    //================================================================
}
