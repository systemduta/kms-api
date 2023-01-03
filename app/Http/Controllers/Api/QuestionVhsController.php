<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriVhs;
use App\Models\QuestionVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama index yang tidak memiliki parameter. Method ini digunakan untuk menampilkan data dari tabel question_vhs dan materi_vhs.
     * Pada baris ketiga, terdapat sebuah query yang melakukan join antara tabel question_vhs dan materi_vhs dengan menggunakan fungsi join. Kemudian, terdapat pemilihan kolom-kolom yang akan ditampilkan dengan menggunakan fungsi select. Fungsi orderBy digunakan untuk mengurutkan data berdasarkan kolom id dari tabel question_vhs. Fungsi get akan mengambil semua data yang dikembalikan oleh query tersebut.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data yang bersangkutan.
     */
    public function index()
    {
        try {
            $data=QuestionVhs::join('materi_vhs',function($join){
                $join->on('question_vhs.materi_id','=','materi_vhs.id');
            })->select('question_vhs.id as id_question','materi_vhs.id','question_vhs.*','materi_vhs.*')->orderBy('question_vhs.id','desc')->get();
            return response()->json(
                [
                    'data' => $data
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    /**
     * Ini adalah sebuah method bernama listMateriVhs yang tidak memiliki parameter. Method ini digunakan untuk menampilkan semua data dari tabel materi_vhs.
     * Pada baris ketiga, terdapat sebuah query yang mengambil semua data dari tabel materi_vhs menggunakan fungsi all.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data yang bersangkutan.
     */
    public function listMateriVhs(){
        try {
            $data=MateriVhs::all();
            return response()->json(
                [
                    'data' => $data
                ]
            );
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
     * Ini adalah sebuah method bernama store yang menerima parameter Request $request. Method ini digunakan untuk menambahkan data ke dalam tabel question_vhs.
     * Pada baris ketiga, terdapat sebuah validasi yang dilakukan dengan menggunakan fungsi make dari class Validator. Fungsi ini akan memvalidasi apakah parameter materi_id dan question telah terisi atau tidak. Jika salah satu dari kedua parameter tersebut belum terisi, maka akan terdapat error yang akan dikembalikan dalam bentuk response.
     * Pada baris kelima hingga kedelapan, terdapat sebuah query yang akan menambahkan data baru ke dalam tabel question_vhs dengan menggunakan fungsi create. Kemudian, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'materi_id' => 'required',
            'question' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        try {
            $data= QuestionVhs::create([
                'materi_id' => $request->materi_id,
                'question' => $request->question,
            ]);
            return response()->json(
                [
                    'data' => "saved successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama show yang menerima parameter $id. Method ini digunakan untuk menampilkan data dari tabel question_vhs dan materi_vhs dengan id tertentu.
     * Pada baris ketiga, terdapat sebuah query yang melakukan join antara tabel question_vhs dan materi_vhs dengan menggunakan fungsi join. Kemudian, terdapat pemilihan kolom-kolom yang akan ditampilkan dengan menggunakan fungsi select. Fungsi where digunakan untuk memfilter data berdasarkan kolom id dari tabel question_vhs yang sesuai dengan parameter $id. Fungsi first akan mengambil satu data yang dikembalikan oleh query tersebut.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data yang bersangkutan.
     */
    public function show($id)
    {
        try {
            $data=QuestionVhs::join('materi_vhs',function($join){
                $join->on('question_vhs.materi_id','=','materi_vhs.id');
            })
            ->select('question_vhs.id as id_question','materi_vhs.id','question_vhs.*','materi_vhs.*')
            ->where('question_vhs.id',$id)
            ->first();
            return response()->json(
                [
                    'data' => $data
                ]
            );
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
     * Ini adalah sebuah method bernama update yang menerima parameter Request $request dan $id. Method ini digunakan untuk mengubah data pada tabel question_vhs dengan id tertentu.
     * Pada baris ketiga, terdapat sebuah validasi yang dilakukan dengan menggunakan fungsi make dari class Validator. Fungsi ini akan memvalidasi apakah parameter materi_id dan question telah terisi atau tidak. Jika salah satu dari kedua parameter tersebut belum terisi, maka akan terdapat error yang akan dikembalikan dalam bentuk response.
     * Pada baris kelima hingga kedelapan, terdapat sebuah query yang akan mengubah data pada tabel question_vhs dengan menggunakan fungsi update. Kemudian, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'materi_id' => 'required',
            'question' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        try {
            $data= QuestionVhs::findOrfail($id)->update([
                'materi_id' => $request->materi_id,
                'question' => $request->question,
            ]);
            return response()->json(
                [
                    'data' => "saved successfully"
                ]
            );
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
     * Ini adalah sebuah method bernama destroy yang menerima parameter $id. Method ini digunakan untuk menghapus data dari tabel question_vhs dengan id tertentu.
     * Pada baris ketiga, terdapat sebuah query yang akan mengambil data pada tabel question_vhs dengan menggunakan fungsi findOrFail. Kemudian, fungsi delete akan menghapus data tersebut dari tabel.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function destroy($id)
    {
        try {
            $delete = QuestionVhs::findOrFail($id);
            $delete->delete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
