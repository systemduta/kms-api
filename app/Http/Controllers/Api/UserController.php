<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 403;

    public function getTotalUsersByOrganization()
    {
        try {
            $auth = auth()->user(); // Assuming you have authentication set up
            $cek = DB::table('permissions')->where('user_id', $auth->id)->where('isSuperAdmin', 1)->first();

            $organizationWithTotalUsers = Organization::select('organizations.name', \DB::raw('COUNT(users.id) AS total_users'))
                ->join('users', 'organizations.id', '=', 'users.organization_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->where('users.status', 1)
                ->groupBy('organizations.id', 'organizations.name');
            if (!$cek) {
                $organizationWithTotalUsers->where('companies.id', $auth->company_id);
            }

            return response()->json([
                'data'      => $organizationWithTotalUsers->get(),
                'message'   => 'Successfully retrieved data',
            ], $this->successStatus);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'data' => $e->getMessage(),
            ], $this->errorStatus);
        }
    }

    public function getTotalUsersByCompany()
    {
        try {
            $auth = auth()->user(); // Assuming you have authentication set up
            $cek = DB::table('permissions')->where('user_id', $auth->id)->where('isSuperAdmin', 1)->first();
            $excludedCompanyIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];

            $companiesWithTotalUsers = Company::select('companies.name', \DB::raw('COUNT(users.id) AS total_users'))
                ->join('users', 'companies.id', '=', 'users.company_id')
                ->whereNotIn('companies.id', $excludedCompanyIds)
                ->where('users.status', 1)
                ->groupBy('companies.id', 'companies.name');
            if (!$cek) {
                $companiesWithTotalUsers->where('companies.id', $auth->company_id);
            }

            return response()->json([
                'data'      => $companiesWithTotalUsers->get(),
                'message'   => 'Successfully retrieved data',
            ], $this->successStatus);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'data' => $e->getMessage(),
            ], $this->errorStatus);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'nik' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 401);
            }

            $user = User::where('id', $request->id)
                ->where('nik', $request->nik)
                ->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->password = bcrypt('12345678');
            $user->save();

            return response()->json([
                'message' => 'Password successfully reset',
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * untuk route:
     *  Route::post('login', 'UserController@login');
     * 
     * fungsi: 
     *  untuk mengatur login
     *  nik -> kalau pakai data nik;
     *  usernmae -> kalau pakai username
     * . dengan syarat parameter isWeb == 1
     * 
     * parameter wajib: 
     *  - nik
     *  - password
     *  - isWeb
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['nik', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $cekPermision = DB::table('permissions')
                ->where('user_id', $user->id)
                ->first();

            if (!$cekPermision) {
                return response()->json(['message' => 'Unauthorized - Akun tidak memiliki akses untuk masuk ke sistem'], 401);
            }

            DB::table('activities')->insert([
                'user_id' => $user->id,
                'time' => Carbon::now(),
                'details' => 'User login'
            ]);

            $company = DB::table('companies')->where('id', $user->company_id)->first();
            $success = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->image,
                'company_name' => $company->name,
                'company_id' => $user->company_id,
                'organization_id' => $user->organization_id,
                'city' => $user->kota,
                'phone' => $user->phone,
                'file' => $user->file,
                'role' => $user->role,
                'isSuperAdmin' => $cekPermision->isSuperAdmin,
                'isSOP' => $cekPermision->isSOP,
                'isKMS' => $cekPermision->isKMS,
                'is1VHS' => $cekPermision->is1VHS,
                'isPAS' => $cekPermision->isPAS,
                'accessToken' => $user->createToken('nApp')->accessToken
            ];
            return response()->json($success, $this->successStatus);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }


    /**
     * hubungan Route: 
     *      Route::post('register', 'UserController@register');
     * 
     * fungsi:
     *      untuk mengatur jika ada user baru.
     *      digunakan oleh admin melalui web, user tidak bisa mendaftar secara mandiri
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'file' => 'required',
            'name' => 'required',
            'password' => 'required',
            'company_id' => 'required',
            'organization_id' => 'required',
            'golongan_id' => 'required|exists:golongans,id',
            'nik' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // return response()->json(['data' => $request->nik], $this->errorStatus);


        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',') + 1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = Str::random(10) . '.' . $ext;
        Storage::disk('public')->put('files/' . $filename, base64_decode($file));
        /* END FILE UPLOAD */

        $username = "";
        $company = DB::table('companies')->where('id', $request->company_id)->first();
        $username = $username . $company->code;
        $org = DB::table('organizations')->where('id', $request->organization_id)->first();
        $username = $username . $org->code;
        $username = $username . $request->nik;

        $cek = DB::table('users')->where('nik', $request->nik)->count();
        if ($cek > 0) return response()->json(['message' => 'NIK sudah terdaftar'], 500);

        DB::beginTransaction();
        $userGetId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'company_id' => $request->company_id,
            'status' => $request->status,
            'image' => '',
            'file' => 'files/' . $filename,
            'organization_id' => $request->organization_id,
            'golongan_id' => $request->golongan_id,
            'nik' => $request->nik,
            'email' => $request->email,
            'username' => $username,
            'role' => 2
        ]);

        if ($request->filled('image')) {
            $imgName = '';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'user_' . uniqid() . '.' . $ext;
            if ($ext == 'png') {
                imagepng($image, public_path() . '/files/' . $imgName, 8);
            } else {
                imagejpeg($image, public_path() . '/files/' . $imgName, 20);
            }
            DB::table('users')->where('id', $userGetId)->update(['image' => $imgName]);
        }
        DB::commit();
        return response()->json(['data' => $userGetId, 'message' => 'Data berhasil disimpan!'], $this->successStatus);
    }

    /**
     * untuk mendapatkan data detail user
     * Auth::user()->id , untuk mendapatkan id user yang login
     */
    public function details()
    {
        $user = User::with(['company', 'organization', 'golongan'])
            ->where('id', Auth::user()->id)
            ->get();
        return response()->json(['message' => 'success', 'data' => $user], $this->successStatus);
    }

    /**
     * untuk mendapatkan detail user berdasarkan id yang diinput
     */
    public function detailsUser($id)
    {
        $user = User::with(['company', 'organization', 'golongan'])
            ->where('id', $id)
            ->first();
        return response()->json(['success' => $user], $this->successStatus);
    }

    /**
     * hubungan route: 
     *      Route::get('get_user', 'UserController@index');
     * fungsi:
     * method awal yang dipanggil,
     * ->when($auth->role!=1, function ($q) use ($auth) {  untuk pembatas hal yang ditampilkan jika role user == 1 maka semua data akan ditampilkan tanpa batas perusahaan (saat ini hanya holding), jika role user != 1 maka yang ditampilkan hanya data dari perusahaan yang sama dengan perusahaan user login
     *  
     */
    public function index()
    {
        $auth = auth()->user();
        $user = User::with(['company', 'organization', 'golongan'])
            ->when($auth->role != 1, function ($q) use ($auth) {
                return $q->where('company_id', $auth->company_id);
            })
            ->where('status', '!=', 0)
            ->orderBy('name', 'ASC')
            ->get();
        return response()->json(['data' => $user]);
    }

    /**
     * untuk update data user
     * parameter wajib: 
     *      - passoword
     */
    public function update(request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company_id' => 'required',
            'organization_id' => 'required',
            'golongan_id' => 'required|exists:golongans,id',
            'nik' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $name = $request->name;
        $nik = $request->nik;
        $company_id = $request->company_id;
        $organization_id = $request->organization_id;
        $golongan_id = $request->golongan_id;
        $email = $request->email;
        // $password = $request->password;

        $filename = null;
        /* START FILE UPLOAD */
        if ($request->filled('file')) {
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',') + 1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = Str::random(10) . '.' . $ext;
            Storage::disk('public')->put('files/' . $filename, base64_decode($file));
        }
        /* END FILE UPLOAD */

        $imgName = null;
        if ($request->filled('image')) {
            $baseString = explode(';base64,', $request->image);
            if (count($baseString) > 1) {
                $image = base64_decode($baseString[1]);
                $image = imagecreatefromstring($image);

                $ext = explode('/', $baseString[0]);
                $ext = $ext[1];
                $imgName = 'user_' . uniqid() . '.' . $ext;
                if ($ext == 'png') {
                    imagepng($image, public_path() . '/files/' . $imgName, 8);
                } else {
                    imagejpeg($image, public_path() . '/files/' . $imgName, 20);
                }
            }
        }

        $user = User::find($id);
        $user->image = $imgName ?? $user->image;
        $user->file = $request->filled('file') ? 'files/' . $filename : $user->file;
        $user->name = $name;
        $user->nik = $nik;
        $user->company_id = $company_id;
        $user->organization_id = $organization_id;
        $user->golongan_id = $golongan_id;
        $user->status = $request->status;
        $user->email = $email;
        $user->resign_date = $request->resign_date;
        if ($request->password) $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(
            [
                'success' => $user,
                'message' => 'Update Successfully'
            ],
            $this->successStatus
        );
    }

    /**
     * untuk menghapus data user berdasarkan id
     */
    public function delete($id)
    {
        $user = User::find($id);
        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'Melakukan hapus user'
        ]);
        $user->delete();
        return response()->json(['message' => 'Delete Successfully']);
    }

    /**
     * untuk user logout 
     */
    public function logout(Request $request)
    {
        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'User logout'
        ]);
        auth()->user()->token()->revoke();
        return response()->json(['message' => 'Logout Success']);
    }
}
