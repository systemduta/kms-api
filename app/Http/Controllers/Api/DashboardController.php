<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public $successStatus   =   200; //variabel yang akan dipanggil saat proses sukses dilakukan
    public $errorStatus     =   403; //variabel yang akan dipanggil saat proses gagal dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Method ini memiliki sebuah variabel $user yang diisi dengan objek user yang sedang login saat ini menggunakan fungsi auth(). Kemudian, method ini mengelola data dari tabel "users", "sops", "courses", dan "jadwalvhs" di database dengan menggunakan fungsi DB::table(). Fungsi DB::table() ini merupakan bagian dari Laravel, sebuah framework PHP yang digunakan untuk membangun aplikasi web.
     * Untuk tabel "users", "sops", dan "courses", method ini akan mengambil jumlah data yang ada di dalamnya. Namun, jika user yang login bukan merupakan admin (dengan role 1), maka data yang diambil hanya data yang memiliki company_id yang sama dengan company_id dari user yang login. Hal ini dapat dilihat dari penggunaan fungsi when() dan closure yang mengandung parameter $q dan $user. Fungsi when() ini akan mengeksekusi closure yang ada di dalamnya jika kondisi yang ditentukan bernilai true.
     * Selain itu, method ini juga mengambil data dari tabel "users" yang terakhir ditambahkan ke database, dengan jumlah maksimal 4 data. Data ini diambil dengan menggunakan fungsi skip(), take(), dan orderBy(). Kemudian, method ini mengembalikan data yang telah diambil dalam bentuk response json dengan menggunakan fungsi response()->json().
     */
    public function index()
    {
        $user = auth()->user();
        $users = DB::table('users')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('company_id', $user->company_id);
                })
            ->count();
        $sop = DB::table('sops')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('company_id', $user->company_id);
                })
            ->count();
        $courses = DB::table('courses')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
            return $q->where('company_id', $user->company_id);
            })
            ->count();
        $vhs = DB::table('jadwalvhs')->count();
        $lastUser = DB::table('users')
            ->join('companies','companies.id','=','users.company_id')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
            return $q->where('company_id', $user->company_id);
            })->skip(0)->take(4)->orderBy('id','DESC')->select('users.*','companies.name as name_company')->get();
        return response()->json(
            [
                'code'      =>'200',
                'users'     =>$users,
                'sop'       =>$sop,
                'courses'   =>$courses,
                'vhs'       =>$vhs,
                'lastuser'  =>$lastUser,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
