<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\Desa;
    use App\Models\RukunTetangga;
    use App\Models\RukunWarga;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class AuthController extends Controller{
    
        //Login Api
        public function login(Request $request){
            $credentials = $request->only('username', 'password');

            $username = $request->input('username');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    $res['message'] = "failed";
                    $res['detail'] = "Username / Password yang anda masukan salah";
                    return response($res,406);
                }
                $checkStatus = User::where('username',$username)->first();
                if($checkStatus->status == 0){
                    $res['message'] = "failed";
                    $res['detail'] = "Akun Anda Masih belum diverifikasi";
                    return response($res,406);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $res['message'] = "success";
            $res['detail'] = "Success Login";
            $res['token'] = $token;
            $res['data'] = Auth()->user();
            return response($res,406);
        }
    

        //Register API
        public function register(Request $request) {
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
                'rt_id' => 'nullable',
                'rw_id' => 'nullable',
                'desa_id' => 'nullable',
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

            //Input Data
            $data = new User;
            $data->nik = $nik;
            $data->name = $name;
            $data->jenis_kelamin = $jenis_kelamin;
            $data->role = 'Warga';
            $data->alamat = $alamat;
            $data->tempat_lahir = $tempat_lahir;
            $data->tanggal_lahir = $tanggal_lahir;
            $data->agama = $agama;
            $data->no_telp = $no_telp;
            $data->status = 0;
            $data->username = $username;
            $data->password = bcrypt($password);
            $data->rt_id = $rt_id;
            $data->rw_id = $rw_id;
            $data->desa_id =$desa_id;

            //Save Data
            if($data -> save()){
                $res['message'] = "success";
                $res['detail'] = "Berhasil Menambahkan Data Warga, Silahkan Tunggu akun diverikfikasi oleh RT";
                $res['data'] = $data;
                return response($res,200);
            }
            else{
                $res['message'] = "failed";
                $res['detail'] = "Gagal menambahkan Data Warga";
                return response($res,406);
            }
        }
    
    
        /**
         * Log the user out (Invalidate the token).
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function logout() {
            auth()->logout();
    
            return response()->json(['message' => 'User successfully signed out']);
        }
    
        /**
         * Refresh a token.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function refresh() {
            return $this->createNewToken(auth()->refresh());
        }
    
    
        /**
         * Get the token array structure.
         *
         * @param  string $token
         *
         * @return \Illuminate\Http\JsonResponse
         */
        protected function createNewToken($token){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user()
            ]);
        }
    }