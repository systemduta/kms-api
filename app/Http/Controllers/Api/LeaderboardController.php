<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public $successStatus = 200; //variable yang akan dipanggil saat eksekusi program berhasil
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function detail(Request $request)
    {
        try {
            $dt = DB::table('user_scores as us')
                ->select('us.id', 'u.nik', 'u.name', 'us.score', 'c.title')
                ->join('users as u', 'u.id', '=', 'us.user_id')
                ->join('courses as c', 'c.id', '=', 'us.course_id')
                ->where('u.golongan_id', $request->golongan_id)
                ->where('us.user_id', $request->user_id)
                ->orderBy('us.score', 'desc')
                ->get();

            $total = DB::table('user_scores as us')
                ->selectRaw('sum(us.score) as point')
                ->join('users as u', 'u.id', '=', 'us.user_id')
                ->join('courses as c', 'c.id', '=', 'us.course_id')
                ->where('u.golongan_id', $request->golongan_id)
                ->where('us.user_id', $request->user_id)
                ->orderBy('point', 'desc')
                ->first();

            return response()->json([
                'data' => $dt,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 500,
            ], 500);
        }
    }

    /**
     * Fungsi index ini digunakan untuk menampilkan data skor dari seluruh user yang telah menyelesaikan tes.
     * Pertama-tama, variabel $auth akan diisi dengan objek user yang sedang login. Kemudian, fungsi ini akan mengambil data dari tabel user_scores dan users dengan menggunakan query JOIN.
     * Jika role dari user yang sedang login bukan 1 (artinya bukan administrator), maka akan ditambahkan syarat untuk hanya menampilkan data user yang memiliki company_id yang sama dengan user yang sedang login.
     * Setelah itu, data yang diambil akan di-group berdasarkan ID user, username, dan nama, dan diurutkan berdasarkan skor (point) terbesar. Kemudian, data yang sudah di-group dan diurutkan tersebut akan dikembalikan sebagai respon dalam bentuk JSON.
     */
    public function index(Request $request)
    {
        //{{ BASEURL }}/api/web/leaderboard?golongan_id=8
        $auth = auth()->user();
        $dt = DB::table('user_scores as us')
            ->leftJoin('users as u', 'u.id', '=', 'us.user_id')
            ->when($auth->role != 1, function ($q) use ($auth) {
                return $q->where('u.company_id', $auth->company_id);
            })
            ->where('u.golongan_id', $request->golongan_id)
            ->groupBy('u.golongan_id', 'us.user_id', 'u.nik', 'u.name') // Group by all selected columns
            ->selectRaw('
                u.golongan_id,
                us.user_id as user_id,   
                u.nik,
                u.name,
                sum(us.score) as point
            ')
            ->orderBy('point', 'desc')
            ->get();


        return response()->json(['data' => $dt]);
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
        try {
            DB::table('user_scores')->where('id', $id)->delete();

            return response()->json([
                'message' => 'Success destroy data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 500,
            ], 500);
        }
    }

    /**
     * Fungsi exam_result ini digunakan untuk menampilkan hasil tes dari seluruh user.
     * Pertama-tama, variabel $user akan diisi dengan objek user yang sedang login. Kemudian, fungsi ini akan mengambil data dari tabel user_scores, users, dan courses dengan menggunakan query JOIN.
     * Jika role dari user yang sedang login bukan 1 (artinya bukan administrator), maka akan ditambahkan syarat untuk hanya menampilkan data user yang memiliki company_id yang sama dengan user yang sedang login.
     * Jika company_id dari user yang sedang login adalah 1 (artinya merupakan user dari administrasi) dan organization_id adalah 11, maka akan ditambahkan syarat untuk hanya menampilkan data yang memiliki tipe kursus yang tidak sama dengan 3. Sebaliknya, jika organization_id adalah 20, maka akan ditambahkan syarat untuk hanya menampilkan data yang memiliki tipe kursus yang sama dengan 3.
     * Setelah itu, data yang diambil akan diurutkan berdasarkan ID user_score dari yang terbaru ke yang terlama, dan dikembalikan sebagai respon dalam bentuk JSON.
     */
    public function exam_result(Request $request)
    {
        $user = auth()->user();
        $result = DB::table('user_scores as us')
            ->leftJoin('users as u', 'u.id', 'us.user_id')
            ->leftJoin('courses as c', 'c.id', 'us.course_id')
            ->selectRaw('
                us.id,
                u.name,
                c.title,
                us.score,
                us.status,
                us.is_pre_test
            ')
            ->when($user->role != 1, function ($q) use ($user) {
                return $q->where('u.company_id', $user->company_id);
            })
            ->when(($user->company_id == 1 && $user->organization_id == 11), function ($query) {
                return $query->where('c.type', '!=', 3);
            })
            ->when(($user->company_id == 1 && $user->organization_id == 20), function ($query) {
                return $query->where('c.type', '=', 3);
            })
            ->orderByDesc('us.id')
            ->get();
        return response()->json(['data' => $result]);
    }
}
