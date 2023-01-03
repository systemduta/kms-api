<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Illuminate\Http\Request;
use App\User;
use Validator;
use DB;

class TestController extends Controller
{
    public $successStatus = 200; //digunakan untuk mengirim statusCode 200 jika 


    /**
     * Ini adalah contoh fungsi store yang menerima parameter Request $request di PHP. Fungsi ini terlihat seperti mengatur data pada tabel test_questions dan test_answers di database.
     * Pertama, fungsi ini memulai transaksi database dengan menggunakan DB::beginTransaction(). Kemudian, fungsi tersebut menambahkan baris baru ke tabel test_questions dan menyimpan ID yang baru dibuat ke variabel $qId.
     * Setelah itu, fungsi tersebut menggunakan perulangan foreach untuk menambahkan baris baru ke tabel test_answers untuk setiap elemen dalam array $request->answers. Setiap baris baru yang ditambahkan akan memiliki test_question_id yang sama dengan $qId, serta nama dan nilai is_true yang diambil dari array $answer.
     * Akhirnya, fungsi tersebut mengakhiri transaksi database dengan DB::commit() dan mengembalikan respons JSON dengan pesan 'OK'.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $qId=DB::table('test_questions')->insertGetId([
            'course_id' => $request->course_id,
            'is_pre_test' => $request->is_pre_test,
            'description' => $request->description,
        ]);
        foreach ($request->answers as $answer) {
            DB::table('test_answers')->insert([
                'test_question_id' => $qId,
                'name' => $answer['name'],
                'is_true' => $answer['is_true'],
            ]);
        }
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    /**
     * Ini adalah contoh fungsi index yang menerima parameter Request $request dan $id di PHP. Fungsi ini terlihat seperti mengambil dan mengembalikan data dari tabel test_questions dan test_answers di database.
     * Pertama, fungsi ini mengambil semua baris dari tabel test_questions yang memiliki course_id sama dengan $id dan mengurutkannya berdasarkan id. Kemudian, fungsi tersebut menggunakan perulangan foreach untuk mengambil data dari tabel test_answers untuk setiap baris dalam hasil query sebelumnya. Setiap baris yang diambil dari tabel test_answers akan disimpan ke dalam variabel $var->answers.
     * Setelah itu, fungsi tersebut menambahkan variabel $var ke dalam array $array dan melanjutkan perulangan. Kemudian, setelah seluruh perulangan selesai, fungsi tersebut mengembalikan respons JSON dengan data yang disimpan dalam $array.
     */
    public function index(Request $request, $id)
    {
        $data = DB::table('test_questions')
        ->where('course_id', $id)
        ->orderBy('id')
        ->get();
        $array=array();
        foreach ($data as $key => $value) {
            $var = $value;
            $var->answers = DB::table('test_answers')->where('test_question_id', $value->id)->select('id','name','is_true')->get();
            array_push($array, $var);
        }
        return response()->json(['data' => $array]);
    }
}
