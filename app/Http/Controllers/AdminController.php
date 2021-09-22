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



class AdminController extends Controller
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
            'desa_id' => 'nullable',
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
        $desa_id = $request->desa_id;

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

        $checkDesa = Desa::where('id',$desa_id)->first();
        if(empty($checkDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Id Desa Tersebut tidak dapat ditemukan";
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
        ->where('desa_id',$desa_id)
        ->first();
        if(empty($checkRelasi)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak berhubungan dengan id Rw dan id Desa";
            return response($res,406);
        }

        if($role == 'RT'){
            $checkEksistensiRt = User::where('role','=','RT')
            ->where('rt_id',$rt_id)
            ->where('rw_id',$rw_id)
            ->where('desa_id',$desa_id)
            ->first();

            if(!empty($checkEksistensiRt)){
                $res['message'] = "failed";
                $res['detail'] = "Dalam RT Tersebut sudah terdapat Ketua RT nya";
                return response($res,406);
            }
        }

        if($role == 'RW'){
            $checkEksistensiRw = User::where('role','=','RW')
            ->where('rw_id',$rw_id)
            ->where('desa_id',$desa_id)
            ->first();
            if(!empty($checkEksistensiRw)){
                $res['message'] = "failed";
                $res['detail'] = "Dalam RW Tersebut sudah terdapat Ketua RW nya";
                return response($res,406);
            }
        }
        
        if($role == 'Desa'){
            $checkEksistensiDesa = User::where('role','=','Desa')
            ->where('desa_id',$desa_id)
            ->first();
            if(!empty($checkEksistensiDesa)){
                $res['message'] = "failed";
                $res['detail'] = "Dalam Desa Tersebut sudah terdapat Ketua Desa nya";
                return response($res,406);
            }
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
        $data->desa_id =$desa_id;

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
            'desa_id' => 'nullable',
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
        $desa_id = $request->desa_id;

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

        $checkDesa = Desa::where('id',$desa_id)->first();
        if(empty($checkDesa)){
            $res['message'] = "failed";
            $res['detail'] = "Id Desa Tersebut tidak dapat ditemukan";
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
        ->where('desa_id',$desa_id)
        ->first();
        if(empty($checkRelasi)){
            $res['message'] = "failed";
            $res['detail'] = "Id Rukun Tetangga Tersebut tidak berhubungan dengan id Rw dan id Desa";
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
        $data->desa_id =$desa_id;

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

        //Check Data
        $checkData = User::where('id',$id)->first();
        if(empty($checkData)){
            $res['message'] = "failed";
            $res['detail'] = "User dengan Id tersebut Tidak Ditemukan";
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
        $data = User::get();

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


    public function showUserFilter(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $role = $request->input('role');
        $tempat_lahir = $request->input('tempat_lahir');
        $tanggal_lahir = $request->input('tanggal_lahir');
        $agama = $request->input('agama');
        $rt_id = $request->input('rt_id');
        $rw_id = $request->input('rw_id');
        $desa_id = $request->input('desa_id');
        $status = $request->input('status');

        $created_at = $request->input('created_at');
        $created_at_from = $request->input('created_at_from');
        $created_at_to = $request->input('created_at_to');

        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;

        $data = User::query()
        ->when(Input::has('id'), function ($query) use ($id) {
            $query->where('id',$id);
        })
        ->when(Input::has('name'), function ($query) use ($name) {
            $query->where('name','like','%'.$name.'%');
        })
        ->when(Input::has('jenis_kelamin'), function ($query) use ($jenis_kelamin) {
            $query->where('jenis_kelamin',$jenis_kelamin);
        })
        ->when(Input::has('role'), function ($query) use ($role) {
            $query->where('role',$role);
        })
        ->when(Input::has('tempat_lahir'), function ($query) use ($tempat_lahir) {
            $query->where('tempat_lahir','like','%'.$tempat_lahir.'%');
        })
        ->when(Input::has('tanggal_lahir'), function ($query) use ($tanggal_lahir) {
            $query->whereDate('tanggal_lahir', '=', $tanggal_lahir);
        })
        ->when(Input::has('agama'), function ($query) use ($agama) {
            $query->where('agama',$agama);
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
            $res['detail'] = "Berhasil mendapatkan data";
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
