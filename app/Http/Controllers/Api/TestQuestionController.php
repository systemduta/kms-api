<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestQuestionController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    /**
     * Ini adalah sebuah function yang bernama show yang mengambil satu parameter bernama $id. Function ini akan mencoba mengambil data dari tabel test_questions dengan id yang sama dengan $id dan mengurutkannya berdasarkan id. Kemudian function akan menggunakan looping foreach untuk mengambil data dari tabel test_answers untuk setiap record yang diperoleh dari tabel test_questions. Setiap record yang diperoleh dari tabel test_answers akan disimpan dalam key answers di dalam record dari tabel test_questions. Kemudian function akan mengembalikan sebuah response dalam bentuk JSON yang berisi key success yang isinya adalah data yang diperoleh dari tabel test_questions dan test_answers yang telah disimpan dalam key answers.
     */
    public function show($id)
    {
        $data = TestQuestion::where('id', $id)->orderBy('id')->get();
        // $array=array();
        foreach ($data as $key => $value) {
            $var = $value;
            $var->answers = DB::table('test_answers')->where('test_question_id', $value->id)->get();
            // array_push($array, $var);
        }
        return response()->json(['success' => $var], $this->successStatus);
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
     * Ini adalah sebuah function update data di dua tabel yaitu tabel test_questions dan test_answers. Pertama, block kode tersebut akan mengupdate data di tabel test_questions dengan menggunakan metode update dari class DB. Metode tersebut akan mencari record yang memiliki id yang sama dengan parameter $id dan mengupdate field is_pre_test dan description sesuai dengan request yang diterima. Kemudian block kode tersebut akan menggunakan looping foreach untuk mengupdate data di tabel test_answers. Setiap record yang ada di dalam array answers dari request yang diterima akan diupdate di tabel test_answers dengan menggunakan metode update dari class DB. Setiap record yang diupdate akan dicari berdasarkan id di tabel test_answers dan kemudian diupdate sesuai dengan data yang ada di dalam record tersebut. Jika proses update berhasil, block kode tersebut akan mengembalikan sebuah response dalam bentuk JSON yang berisi key message yang isinya adalah string 'Data berhasil di Update'. Jika terjadi exception (error) saat proses update, block kode tersebut akan mengembalikan sebuah response dalam bentuk JSON yang berisi key message yang isinya adalah pesan error yang terjadi.
     */
    public function update(Request $request, $id)
    {
        DB::table('test_questions')->where('id',$id)->update([
            'is_pre_test' => $request->is_pre_test,
            'description' => $request->description,
        ]);
        try{
            foreach ($request->answers as $key) {
                DB::table('test_answers')->where('id',$key['id'])->update([
                    'id'      => $key['id'],
                    'name'    => $key['name'],
                    'is_true' => $key['is_true'],
                ]);
            }
            return response()->json([
                'message' => 'Data berhasil di Update'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => $e
            ]);
        }
        // $comments = $request->answers;
        // //return $comments;
        // if (! empty($comments))
        // {
        //     // return $comments;
        //     foreach ($comments as $key) {
        //         DB::table('test_answers')->where('id',$key['id'])->update([
        //             'name' => $key['name'],
        //             'is_true' => $key['is_true'],
        //         ]);
        //     }
        //     return response()->json([
        //         'message' => 'Data berhasil di Update!',
        //     ], 201);
        // }
        // else
        // {
        //     return response()->json([
        //         'message' => 'Data gagal di Update!',
        //     ], 201);
        // }

        return response()->json([ 'message' => 'Data berhasil di Update!']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah function yang menerima request HTTP dengan method DELETE. Function ini akan mencoba menghapus data di dua tabel yaitu tabel test_questions dan test_answers. Pertama, function akan mengambil data dari tabel test_questions dengan id yang sama dengan $id dan mengambil field id dari record tersebut. Kemudian function akan menghapus semua record di tabel test_answers yang memiliki test_question_id yang sama dengan id yang telah diperoleh sebelumnya. Selanjutnya function akan menghapus record di tabel test_questions yang memiliki id yang sama dengan $id. Kemudian function akan mengembalikan sebuah response dalam bentuk JSON yang berisi key message yang isinya adalah string 'delete successfully'.
     */
    public function destroy($id)
    {
        $tq = DB::table('test_questions')->where('id',$id)->select('id')->get();
        DB::table('test_answers')->where('test_question_id',$tq[0]->id)->select('id')->delete();
        DB::table('test_questions')->where('id',$id)->select('id')->delete();
        return response()->json([
            'message' => 'delete successfully'
        ], $this->successStatus);
    }
}
