<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Desa;
use App\Models\RukunWarga;
use App\Models\RukunTetangga;
use App\Models\Surat;
use App\Models\Laporan;
use App\Models\Kegiatan;
use App\Models\Berita;
use Carbon\Carbon;
use StdClass;

class RtController extends Controller
{
    // API CRUD USER

    public function RegisterUser(Request $request){
        $this->validate($request,[
            'nik' => 'required',
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'username' => 'required',
            'password' => 'required',
            'status' => 'nullable',
        ]);

        // Request Input
        $nik = $request->nik;
        $name = $request->name;
        $jenis_kelamin = $request->jenis_kelamin;
        $tempat_lahir = $request->tempat_lahir;
        $tanggal_lahir = Carbon::parse($request->tangal_lahir)->format('Y-m-d');
        $agama = $request->agama;
        $alamat = $request->alamat;
        $no_telp = $request->no_telp;
        $username = $request->username;
        $password = $request->password;
        $rt_id = $request->rt_id;
        $rw_id = $request->rw_id;
        
        //Inisialisasi ID Desa
        $idDesa = Auth()->user()->desa_id;
        $idRw = Auth()->user()->rw_id;
        $idRt = Auth()->user()->rt_id;

        //Check Data
        $checkNik = User::where('nik',$nik)->first();
        if(!empty($checkNik)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan NIK Tersebut sudah terdaftar";
            return response($res,406);
        }

        $checkUsername = User::where('username',$username)->first();
        if(!empty($checkUsername)){
            $res['message'] = "failed";
            $res['detail'] = "Username Tersebut Sudah digunakan";
            return response($res,406);
        }

        $checkNoTelp = User::where('no_telp',$no_telp)->first();
        if(!empty($checkNoTelp)){
            $res['message'] = "failed";
            $res['detail'] = "Nomor Telepon Tersebut Sudah digunakan";
            return response($res,406);
        }




        //Input Data
        $data = new User;
        $data->nik = $nik;
        $data->name = $name;
        $data->jenis_kelamin = $jenis_kelamin;
        $data->role = 1;
        $data->alamat = $alamat;
        $data->tempat_lahir = $tempat_lahir;
        $data->tanggal_lahir = $tanggal_lahir;
        $data->agama = $agama;
        $data->no_telp = $no_telp;
        $data->status = 1;
        $data->username = $username;
        $data->password = bcrypt($password);
        $data->rt_id = $idRt;
        $data->rw_id = $idRw;
        $data->desa_id = $idDesa;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menambahkan Data User, Silahkan Login";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal menambahkan Data User";
            return response($res,406);
        }
    }






    public function updateUser(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'nik' => 'required',
            'name' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'username' => 'required',
            'password' => 'required',
            'status' => 'nullable',
        ]);

        // Request Input
        $id = $request->id;
        $nik = $request->nik;
        $name = $request->name;
        $jenis_kelamin = $request->jenis_kelamin;
        $tempat_lahir = $request->tempat_lahir;
        $tanggal_lahir = Carbon::parse($request->tangal_lahir)->format('Y-m-d');
        $agama = $request->agama;
        $alamat = $request->alamat;
        $no_telp = $request->no_telp;
        $username = $request->username;
        $password = $request->password;

        //Inisialisasi ID Desa
        $idDesa = Auth()->user()->desa_id;
        $idRw = Auth()->user()->rw_id;
        $idRt = Auth()->user()->rt_id;

        //Check Data
        $checkNik = User::where('nik',$nik)->first();
        if(!empty($checkNik)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan NIK Tersebut sudah terdaftar";
            return response($res,406);
        }

        $checkUsername = User::where('username',$username)->first();
        if(!empty($checkUsername)){
            $res['message'] = "failed";
            $res['detail'] = "Username Tersebut Sudah digunakan";
            return response($res,406);
        }

        $checkNoTelp = User::where('no_telp',$no_telp)->first();
        if(!empty($checkNoTelp)){
            $res['message'] = "failed";
            $res['detail'] = "Nomor Telepon Tersebut Sudah digunakan";
            return response($res,406);
        }

        if($role > 1){
            $res['message'] = "failed";
            $res['detail'] = "Ketua Rt hanya bisa mendaftarkan Warga";
            return response($res,406);
        }

        //Edit Data
        $data = User::find($id);
        $data->nik = $nik;
        $data->name = $name;
        $data->jenis_kelamin = $jenis_kelamin;
        $data->role = 1;
        $data->alamat = $alamat;
        $data->tempat_lahir = $tempat_lahir;
        $data->tanggal_lahir = $tanggal_lahir;
        $data->agama = $agama;
        $data->no_telp = $no_telp;
        $data->status = 1;
        $data->username = $username;
        $data->password = bcrypt($password);
        $data->rt_id = $idRt;
        $data->rw_id = $idRw;
        $data->desa_id =$idDesa;

        //Save Data
        if($data -> save()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Mengubah Data User";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mengubah Data User";
            return response($res,406);
        }
    }




    public function deleteUser(Request $request){
        $this->validate($request,[
            'id' => 'required',
        ]);

        //Request Input
        $id = $request->input('id');

        //Inisialisasi ID Desa
        $idDesa = Auth()->user()->desa_id;
        $idRw = Auth()->user()->rw_id;
        $idRt = Auth()->user()->rt_id;
        
        //Check Data
        $checkData = User::where('id',$id)
        ->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkData2 = User::where('id',$id)
        ->where('rt_id',$idRt)
        ->where('rw_id',$idRw)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan Id tersebut bukan dari RT Anda";
            return response($res,406);
        }

        //find Data
        $data = User::find($id);

        //Delete Data
        if($data -> delete()){
            $res['message'] = "success";
            $res['detail'] = "Berhasil Menghapus Data User";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Menghapus Data User";
            return response($res,406);
        }
    }


    public function showUser(){

        //Inisialisasi ID 
        $idDesa = Auth()->user()->desa_id;
        $idRw = Auth()->user()->rw_id;
        $idRt = Auth()->user()->rt_id;

        $dataUser = User::where('rt_id',$idRt)
        ->where('rw_id',$idRw)
        ->where('desa_id',$idDesa)
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
        $data->dataUser = $dataUser;

        if($data){
            $res['message'] = "success";
            $res['detail'] = "Data Semua User";
            $res['data'] = $data;
            return response($res,200);
        }
        else{
            $res['message'] = "failed";
            $res['detail'] = "Gagal Mendapat Data User";
            return response($res,406);
        }
    }

    //================================================================
}
