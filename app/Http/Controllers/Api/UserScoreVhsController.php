<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\UserScoreVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserScoreVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah function yang bernama index yang tidak mengambil parameter apapun. Function ini akan mencoba mengambil data dari database dengan membuat sebuah query yang menggabungkan (join) beberapa tabel di database. Query tersebut akan mengambil data dari tabel users, user_score_vhs, companies, materi_vhs, question_vhs, dan answer_vhs dan menyimpan hasilnya dalam variabel $data. Kemudian function ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi key success yang isinya adalah data yang didapat dari query tersebut dan key message yang isinya adalah string 'get successfully'. Jika terjadi exception (error) saat mengambil data dari database, function ini akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error yang terjadi.
     */
    public function index()
    {        
        try {
            $data = DB::table('users')
                    ->join('user_score_vhs','user_score_vhs.user_id','users.id')
                    ->join('companies','companies.id','users.company_id')
                    ->join('materi_vhs','materi_vhs.id','user_score_vhs.materi_id')
                    ->join('question_vhs','question_vhs.materi_id','materi_vhs.id')
                    ->join('answer_vhs','answer_vhs.question_id','question_vhs.id')
                    ->select('user_score_vhs.id',
                    'users.name as nama_user',
                    'users.username as username',
                    'materi_vhs.name as namamateri',
                    'question_vhs.question as question',
                    'answer_vhs.answer as answer',
                    'materi_vhs.type',
                    'user_score_vhs.score')
                    ->get();
            return response()->json([
                'success'=>$data,
                'message'=>'get successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    /**
     * Ini adalah sebuah function yang bernama getUserPerCompany yang mengambil satu parameter bernama $id. Function ini akan mencoba mengambil data dari database dengan membuat sebuah query yang menggabungkan (join) beberapa tabel di database. Query tersebut akan mengambil data dari tabel users, companies, user_score_vhs, materi_vhs, question_vhs, dan answer_vhs dan menyimpan hasilnya dalam variabel $data. Query tersebut juga akan menggunakan parameter $id sebagai filter untuk mengambil hanya data yang sesuai dengan company_id di tabel users yang sama dengan $id. Selain itu, function ini juga akan mengambil data dari tabel companies yang id sama dengan parametr $id dan menyimpannya dalam variabel $company. Kemudian function ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi key company yang isinya adalah data yang didapat dari query terhadap tabel companies, key success yang isinya adalah data yang didapat dari query terhadap tabel-tabel lainnya, dan key message yang isinya adalah string 'get successfully'. Jika terjadi exception (error) saat mengambil data dari database, function ini akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error yang terjadi.
     */
    public function getUserPerCompany($id) {
        try {

            $data=DB::table('users')
                    ->join('companies','companies.id','=','users.company_id')
                    ->join('user_score_vhs','user_score_vhs.user_id','=','users.id')
                    ->join('materi_vhs','materi_vhs.id','=','user_score_vhs.materi_id')
                    ->join('question_vhs','question_vhs.materi_id','=','materi_vhs.id')
                    ->join('answer_vhs','answer_vhs.question_id','=','question_vhs.id')
                    ->select('users.name as nama_user','users.username as username','companies.name as nama_company','materi_vhs.name as nama_materi','question_vhs.question as question','user_score_vhs.score','answer_vhs.answer','materi_vhs.type')
                    ->where('users.company_id',$id)
                    ->get();
            $company = Company::where('id',$id)->get();
            return response()->json([
                   'company'=>$company,
                   'success'=>$data,
                   'message'=>'get successfully']);        
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
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

    /**
     * Ini adalah sebuah function yang menerima request HTTP dengan method POST. Pertama, function ini akan memvalidasi request yang diterimanya dengan menggunakan class Validator dari library Laravel. Validasi tersebut akan memastikan bahwa parameter materi_id, user_id, dan score harus diisi. Jika validasi gagal (ada field yang tidak sesuai dengan validasi yang ditetapkan), function akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error dari validasi tersebut.
     * Setelah validasi selesai, function akan mengecek apakah sudah ada data dengan user_id dan materi_id yang sama di tabel user_score_vhs. Jika tidak ada, function akan melanjutkan dengan menambahkan data baru ke tabel user_score_vhs. Function akan menggunakan metode insertGetId dari class DB untuk menambahkan data ke tabel tersebut dan mengembalikan ID dari data yang baru saja ditambahkan. Kemudian function akan mengembalikan response dalam bentuk JSON yang berisi key success yang isinya adalah ID dari data yang baru saja ditambahkan dan key message yang isinya adalah string 'get successfully'. Jika terjadi exception (error) saat menambahkan data ke tabel, function ini akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error yang terjadi. Jika sudah ada data dengan user_id dan materi_id yang sama di tabel user_score_vhs, function akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah string 'user sudah dinilai'.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'materi_id'             => 'required',
            'user_id'               => 'required',
            'score'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        $cekData = UserScoreVhs::where('user_id',$request->user_id)->where('materi_id',$request->materi_id)->count();
        if ($cekData==0) {
            try {
                DB::beginTransaction();
                $data = DB::table('user_score_vhs')->insertGetId([
                    'materi_id'         => $request->materi_id,
                    'user_id'           => $request->user_id,
                    'score'             => $request->score,
                    'status'             => "1",                    
                ]);
                DB::commit();
    
                return response()->json([
                    'success'=>$data,
                    'message'=>'get successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } 
        } else {
            return response()->json(['error' => 'user sudah dinilai'], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah function yang bernama show yang mengambil satu parameter bernama $id. Function ini akan mencoba mengambil data dari database dengan membuat sebuah query yang menggabungkan (join) beberapa tabel di database. Query tersebut akan mengambil data dari tabel user_score_vhs, users, companies, materi_vhs, question_vhs, dan answer_vhs dan menyimpan hasilnya dalam variabel $data. Query tersebut juga akan menggunakan parameter $id sebagai filter untuk mengambil hanya data yang sesuai dengan id di tabel user_score_vhs yang sama dengan $id. Kemudian function ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi key success yang isinya adalah data yang didapat dari query tersebut dan key message yang isinya adalah string 'get successfully'. Jika terjadi exception (error) saat mengambil data dari database, function ini akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error yang terjadi.
     */
    public function show($id)
    {
        try {
            $data=DB::table('user_score_vhs')
                ->join('users','user_score_vhs.user_id','=','users.id')
                ->join('companies','companies.id','=','users.company_id')
                ->join('materi_vhs','materi_vhs.id','=','user_score_vhs.materi_id')
                ->join('question_vhs','question_vhs.materi_id','=','materi_vhs.id')
                ->join('answer_vhs','answer_vhs.user_id','=','users.id')
                ->select('user_score_vhs.id as user_score_vhs_id','users.id as users_id','materi_vhs.id as id_materi_vhs','users.name as nama_user','users.username as username','companies.name as nama_company','materi_vhs.name as nama_materi','question_vhs.question as question','user_score_vhs.score','answer_vhs.answer','materi_vhs.type')
                ->where('user_score_vhs.id',$id)
                ->get();
            return response()->json([
                'success'=>$data,
                'message'=>'get successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
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
    /**
     * Ini adalah sebuah function yang menerima request HTTP dengan method PUT. Pertama, function ini akan memvalidasi request yang diterimanya dengan menggunakan class Validator dari library Laravel. Validasi tersebut akan memastikan bahwa parameter materi_id, user_id, dan score harus diisi. Jika validasi gagal (ada field yang tidak sesuai dengan validasi yang ditetapkan), function akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error dari validasi tersebut
     * Setelah validasi selesai, function akan mencoba mengubah data di tabel user_score_vhs yang memiliki id yang sama dengan parameter $id. Function akan menggunakan metode findOrfail dari class UserScoreVhs untuk mengambil data tersebut dan kemudian menggunakan metode update untuk mengubah data tersebut sesuai dengan request yang diterima. Kemudian function akan mengembalikan sebuah response dalam bentuk JSON yang berisi key success yang isinya adalah hasil dari proses update dan key message yang isinya adalah string 'get successfully'. Jika terjadi exception (error) saat mengupdate data di tabel, function ini akan mengembalikan response dalam bentuk JSON yang berisi key error yang isinya adalah pesan error yang terjadi.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'materi_id'             => 'required',
            'user_id'               => 'required',
            'score'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

            try {
                $data = UserScoreVhs::findOrfail($id)->update([
                    'materi_id'         => $request->materi_id,
                    'user_id'           => $request->user_id,
                    'score'             => $request->score,
                ]);
    
                return response()->json([
                    'success'=>$data,
                    'message'=>'get successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
      * Ini adalah sebuah function yang menerima request HTTP dengan method DELETE. Function ini akan mencoba menghapus data di tabel user_score_vhs yang memiliki id yang sama dengan parameter $id. Function akan menggunakan metode destroy dari class UserScoreVhs untuk menghapus data tersebut. Kemudian function akan mengembalikan sebuah response dalam bentuk JSON yang berisi key message yang isinya adalah string 'Data Berhasil di Hapus'. Jika terjadi exception (error) saat menghapus data di tabel, function ini akan mengembalikan sebuah response dalam bentuk HttpException dengan kode status 500 (server error) dan pesan error yang terjadi.
      */
    public function destroy($id)
    {
        try {
            UserScoreVhs::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }
}
