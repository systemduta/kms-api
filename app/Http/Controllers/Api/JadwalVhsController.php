<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwalvhs;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalVhsController extends Controller
{
    
    public $successStatus = 200; //variabel ayng akan dipangggil saat operasi sukses dilakukan
    public $errorStatus = 403; //variabel yang akan di panggil saat operasi gagal dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Ini adalah method yang digunakan untuk mengambil semua data jadwalvhs dari database dan mengembalikan data tersebut dalam bentuk JSON ke client. Method ini menggunakan try-catch untuk menangani kemungkinan kesalahan yang terjadi selama proses.
     * Method ini menggunakan class Jadwalvhs untuk mengakses method orderBy() dan get(). Method orderBy() digunakan untuk mengurutkan data jadwalvhs berdasarkan kolom id dengan urutan DESC (descending). Method get() digunakan untuk mengambil semua data yang diurutkan dari database.
     * Jika proses berhasil, maka method ini akan mengembalikan respon dengan data jadwalvhs dalam format JSON ke client menggunakan helper response() dengan method json(). Jika terjadi kesalahan, maka method ini akan mengembalikan respon dengan pesan kesalahan dan status kode 500 (internal server error) ke client.
     */
    public function sop_all(){
        try {
            return response()->json(['data' => Jadwalvhs::orderBy("id",'DESC')->get()]);
        } catch (\Exception $error) {
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
     * Ini adalah method yang digunakan untuk menyimpan data jadwalvhs baru ke dalam database. Method ini menerima request dari client yang berisi data yang akan disimpan, dan mengembalikan respon ke client setelah proses penyimpanan selesai.
     * Method ini pertama-tama menggunakan helper Validator untuk memvalidasi data yang diterima dari request. Jika data tidak valid, maka method ini akan mengembalikan respon dengan pesan error dan status kode 400 (bad request).
     * Setelah data tervalidasi, method ini akan menyimpan data ke dalam database menggunakan transaksi. Transaksi digunakan untuk memastikan bahwa semua proses penyimpanan berhasil atau tidak sama sekali, sehingga database tidak terjadi keadaan "tidak terpakai" (invalid). Method ini menggunakan method insertGetId() dari class DB untuk menyimpan data ke dalam tabel jadwalvhs, lalu mengembalikan id dari data yang baru saja disimpan. Jika terjadi kesalahan selama proses, maka method ini akan mengembalikan respon dengan pesan kesalahan dan status kode 500 (internal server error) ke client.
     * Jika proses penyimpanan berhasil, maka method ini akan mengembalikan respon dengan id data yang baru saja disimpan dan pesan "Data Berhasil disimpan!" serta status kode successStatus ke client.
     */
    public function store(Request $request)
    {
        // error_reporting(0);
        $validator = Validator::make($request->all(),[
            'name'              => 'required',
            'batch'             => 'required',
            'start'             => 'required',
            'end'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        $auth   = auth()->user();

        try {
            DB::beginTransaction();
            $JadwalGetId = DB::table('jadwalvhs')->insertGetId([
                'name'        => $request->name,
                'batch'       => $request->batch,
                'start'       => $request->start,
                'end'         => $request->end,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $JadwalGetId,
            'message'   => 'Data Berhasil disimpan!'
        ], $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah method yang digunakan untuk mengambil data jadwalvhs dari database berdasarkan id yang diberikan. Method ini akan mengembalikan respon ke client setelah proses pengambilan selesai.
     * Method ini menggunakan class DB dan method where() untuk mengambil data jadwalvhs dari tabel jadwalvhs berdasarkan id yang diberikan. Method first() digunakan untuk mengambil hanya satu baris data yang ditemukan. Jika data tidak ditemukan, maka method ini akan mengembalikan nilai null.
     * Jika data ditemukan, maka method ini akan mengembalikan respon dengan data jadwalvhs dalam bentuk JSON dan status kode successStatus ke client. Jika data tidak ditemukan, maka method ini akan mengembalikan respon dengan pesan error "data not found" dan status kode errorStatus ke client.
     */
    public function show($id)
    {
        $data = DB::table('jadwalvhs')->where('id',$id)->first();
        if ($data) {
            return response()->json(['success' => $data], $this->successStatus);
        } else {
            return response()->json(['error' => "data not found"], $this->errorStatus);
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
     * Ini adalah method yang digunakan untuk memperbarui data jadwalvhs yang tersimpan di database. Method ini menerima request dari client yang berisi data baru yang akan diupdate, dan mengembalikan respon ke client setelah proses pembaruan selesai.
     * Method ini pertama-tama menggunakan method find() dari class Jadwalvhs untuk menemukan data jadwalvhs berdasarkan id yang diberikan. Kemudian, method ini mengubah nilai dari beberapa atribut data jadwalvhs tersebut sesuai dengan data yang diterima dari request. Setelah itu, method save() digunakan untuk menyimpan perubahan tersebut ke dalam database.
     * Jika proses pembaruan berhasil, maka method ini akan mengembalikan respon dengan data jadwalvhs yang telah diupdate dan pesan "update successfully" serta status kode successStatus ke client. Jika terjadi kesalahan selama proses, maka method ini akan mengembalikan respon dengan pesan kesalahan dan status kode 500 (internal server error) ke client.
     */
    public function update(Request $request, $id)
    {
        $name = $request->name;
        $batch = $request->batch;
        $start = $request->start;

        $jadwal               = Jadwalvhs::find($id);
        $jadwal->name         = $name;
        $jadwal->batch       = $batch;
        $jadwal->start        = $start;
        $jadwal->save();

        try {
            $jadwal               = Jadwalvhs::find($id);
        $jadwal->name         = $name;
        $jadwal->batch       = $batch;
        $jadwal->start        = $start;
        $jadwal->save();

        return response()->json([
            'success'=>$jadwal,
            'message'=>'update successfully'],
        $this->successStatus);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah method yang digunakan untuk menghapus data jadwalvhs dari database berdasarkan id yang diberikan. Method ini akan mengembalikan respon ke client setelah proses penghapusan selesai.
     * Method ini pertama-tama menggunakan method find() dari class Jadwalvhs untuk menemukan data jadwalvhs berdasarkan id yang diberikan. Jika data ditemukan, maka method destroy() digunakan untuk menghapus data tersebut dari database. Kemudian, method ini mengembalikan respon dengan pesan "Data Berhasil di Hapus" ke client. Jika data tidak ditemukan, maka method ini akan mengembalikan respon dengan pesan error "data not delete yet" dan status kode errorStatus ke client.
     */
    public function destroy($id)
    {
        $idDestroy =Jadwalvhs::find($id);
        if ($idDestroy) {
            Jadwalvhs::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } else {
            return response()->json(['error' => "data not delete yet"], $this->errorStatus);
        }
        
    }
}
