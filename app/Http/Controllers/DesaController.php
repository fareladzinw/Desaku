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

class DesaController extends Controller
{

    // API CRUD USER

    public function RegisterUser(Request $request){
        $this->validate($request,[
            'nik' => 'required',
            'name' => 'required',
            'role' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'username' => 'required',
            'password' => 'required',
            'rt_id' => 'nullable',
            'rw_id' => 'nullable',
            'status' => 'nullable',
        ]);

        // Request Input
        $nik = $request->nik;
        $name = $request->name;
        $role = $request->role;
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


        $checkRw = RukunWarga::where('id',$rw_id)->first();
        if(empty($checkRw)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Warga Tersebut tidak dapat ditemukan";
            return response($res,406);
        }

        $checkRt = RukunTetangga::where('id',$rt_id)->first();
        if(empty($checkRt)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak dapat ditemukan";
            return response($res,406);
        }

        $checkRelasi = RukunTetangga::where('id',$rt_id)
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkRelasi)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak berhubungan dengan id Rw dan id Desa";
            return response($res,406);
        }

        $checkEksistensiRt = User::where('role','=','RT')
        ->where('rt_id',$rt_id)
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiRt)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam RT Tersebut sudah terdapat Ketua RT nya";
            return response($res,406);
        }

        $checkEksistensiRw = User::where('role','=','RW')
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiRw)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam RW Tersebut sudah terdapat Ketua RW nya";
            return response($res,406);
        }

        $checkEksistensiDesa = User::where('role','=','Desa')
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam Desa Tersebut sudah terdapat Ketua Desa nya";
            return response($res,406);
        }

        if($role > 3){
            $res['message'] = "failed";
            $res['detail'] = "Kepala Desa hanya bisa mendaftarkan Warga, RT, dan RW";
            return response($res,406);
        }



        //Input Data
        $data = new User;
        $data->nik = $nik;
        $data->name = $name;
        $data->jenis_kelamin = $jenis_kelamin;
        $data->role = $role;
        $data->alamat = $alamat;
        $data->tempat_lahir = $tempat_lahir;
        $data->tanggal_lahir = $tanggal_lahir;
        $data->agama = $agama;
        $data->no_telp = $no_telp;
        $data->status = 1;
        $data->username = $username;
        $data->password = bcrypt($password);
        $data->rt_id = $rt_id;
        $data->rw_id = $rw_id;
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
            'role' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'username' => 'required',
            'password' => 'required',
            'rt_id' => 'nullable',
            'rw_id' => 'nullable',
            'status' => 'nullable',
        ]);

        // Request Input
        $id = $request->id;
        $nik = $request->nik;
        $name = $request->name;
        $role = $request->role;
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


        $checkRw = RukunWarga::where('id',$rw_id)->first();
        if(empty($checkRw)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Warga Tersebut tidak dapat ditemukan";
            return response($res,406);
        }

        $checkRt = RukunTetangga::where('id',$rt_id)->first();
        if(empty($checkRt)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak dapat ditemukan";
            return response($res,406);
        }

        $checkRelasi = RukunTetangga::where('id',$rt_id)
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkRelasi)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak berhubungan dengan id Rw dan id Desa";
            return response($res,406);
        }

        
        $checkEksistensiRt = User::where('role','=','RT')
        ->where('rt_id',$rt_id)
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiRt)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam RT Tersebut sudah terdapat Ketua RT nya";
            return response($res,406);
        }

        $checkEksistensiRw = User::where('role','=','RW')
        ->where('rw_id',$rw_id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiRw)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam RW Tersebut sudah terdapat Ketua RW nya";
            return response($res,406);
        }

        $checkEksistensiDesa = User::where('role','=','Desa')
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkEksistensiDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Dalam Desa Tersebut sudah terdapat Ketua Desa nya";
            return response($res,406);
        }

        if($role > 3){
            $res['message'] = "failed";
            $res['detail'] = "Kepala Desa hanya bisa mendaftarkan Warga, RT, dan RW";
            return response($res,406);
        }

        //Edit Data
        $data = User::find($id);
        $data->nik = $nik;
        $data->name = $name;
        $data->jenis_kelamin = $jenis_kelamin;
        $data->role = $role;
        $data->alamat = $alamat;
        $data->tempat_lahir = $tempat_lahir;
        $data->tanggal_lahir = $tanggal_lahir;
        $data->agama = $agama;
        $data->no_telp = $no_telp;
        $data->status = 1;
        $data->username = $username;
        $data->password = bcrypt($password);
        $data->rt_id = $rt_id;
        $data->rw_id = $rw_id;
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
        
        //Check Data
        $checkData = User::where('id',$id)
        ->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan Id tersebut Tidak Ditemukan";
            return response($res,406);
        }

        $checkData2 = User::where('id',$id)
        ->where('desa_id',$idDesa)
        ->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan Id tersebut bukan dari Desa Anda";
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

        //Inisialisasi ID Desa
        $idDesa = Auth()->user()->desa_id;

        $dataUser = User::where('desa_id',$idDesa)
        ->with('rt')
        ->with('rw')
        ->get();

        $dataDesa = Desa::where('id',$idDesa)
        ->get();

        $data = new StdClass();
        $data->dataDesa = $dataDesa;
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
