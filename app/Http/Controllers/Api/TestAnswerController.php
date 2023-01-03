<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestAnswerController extends Controller
{
    public $successStatus = 200;  //untuk mengirim code 200 jika suatau proses sukses dilakukan
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
     * Ini adalah contoh fungsi show yang menerima parameter $id di PHP. Fungsi ini terlihat seperti mengambil dan mengembalikan satu baris data dari tabel test_answers di database yang memiliki id yang sama dengan $id.
     * Fungsi ini mengambil satu baris dari tabel test_answers yang memiliki id yang sama dengan $id dengan menggunakan DB::table('test_answers')->where('id',$id)->first(). Kemudian, fungsi tersebut mengembalikan respons JSON dengan data yang dihasilkan dari query sebelumnya dan nilai dari variabel $this->successStatus.
     */
    public function show($id)
    {
        $answer = DB::table('test_answers')->where('id',$id)->first();
        return response()->json(['success' => $answer], $this->successStatus);
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
     * Ini adalah contoh fungsi update yang menerima parameter Request $request dan $id di PHP. Fungsi ini terlihat seperti memperbarui data pada tabel test_answers di database yang memiliki id yang sama dengan $id.
     * Fungsi ini memperbarui data pada tabel test_answers dengan menggunakan method update() pada model TestAnswer. Method ini akan memperbarui kolom name dan is_true dengan nilai yang diterima dari $request. Kemudian, fungsi tersebut mengembalikan respons JSON dengan pesan sukses.
     */
    public function update(Request $request, $id)
    {
        TestAnswer::where('id',$id)->update([
            'name'      => $request->name,
            'is_true'   => $request->is_true,
        ]);

        return response()->json([
            'message'=>'update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah contoh fungsi destroy yang menerima parameter Request $request dan $id di PHP. Fungsi ini terlihat seperti menghapus satu baris data dari tabel test_answers di database yang memiliki id yang sama dengan $id.
     * Fungsi ini menghapus satu baris dari tabel test_answers yang memiliki id yang sama dengan $id dengan menggunakan DB::table('test_answers')->where('id',$id)->delete(). Kemudian, fungsi tersebut mengembalikan respons JSON dengan pesan sukses. Bagian yang dikomentari adalah contoh penanganan exception yang dapat dilakukan saat proses penghapusan data. Jika terjadi exception, fungsi tersebut akan mengembalikan respons JSON dengan pesan error yang terjadi.
     */
    public function destroy(Request $request, $id)
    {
        DB::table('test_answers')->where('id',$id)->delete();
            return response()->json([
                'message' => 'delete successfully'
            ]);
        // try{
        //     DB::table('test_answers')->where('id',$id)->delete();
        //     return response()->json([
        //         'message' => 'delete successfully'
        //     ]);
        // }catch(Exception $e){
        //     return response()->json([
        //         'message' => $e
        //     ]);
        // }
    }
}
