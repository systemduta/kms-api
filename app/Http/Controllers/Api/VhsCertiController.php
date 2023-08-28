<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vhs_certi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VhsCertiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama index yang mengembalikan sebuah response dalam bentuk JSON. Method ini akan mengambil data dari tabel vhs_certis dan users di database, menyatukannya, dan mengembalikan data tersebut. Kemudian, data tersebut akan dikembalikan dalam bentuk JSON dengan key Message dan Data. Jika terjadi exception (error), maka akan dikembalikan response dalam bentuk JSON dengan key message.
     */
    public function index()
    {
        try {
            $data = DB::table('vhs_certis')
                ->join('users', 'users.id', '=', 'vhs_certis.user_id')
                ->select('vhs_certis.*', 'users.id as idUser', 'users.name as usersname')
                ->get();
            return response()->json([
                'Message'   => 'success',
                'Data'      => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
     * Ini adalah sebuah method yang akan memvalidasi input yang dikirim dari client dengan menggunakan Validator class. Jika input tidak valid, maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP 401 (Unauthorized). Kemudian, method ini akan mengecek apakah client mengirimkan file dengan nama doc1, doc2, dan doc3. Jika file tersebut ada, maka file tersebut akan disimpan ke dalam folder file/certivhs/doc1, file/certivhs/doc2, atau file/certivhs/doc3, dan nama file tersebut akan disimpan ke dalam variabel $doc1, $doc2, atau $doc3. Jika file tidak ditemukan, maka akan disimpan string "not found" ke dalam variabel tersebut. Setelah itu, method ini akan menyimpan data ke dalam tabel vhs_certis di database. Jika proses penyimpanan data berhasil, maka akan dikembalikan response dalam bentuk JSON dengan key data.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($request->hasFile('doc1')) {
            $fileEXT    = $request->file('doc1')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc1')->getClientOriginalExtension();
            $doc1       = $filename . '_' . time() . '.' . $EXT;
            $path1      = $request->file('doc1')->move(public_path('file/certivhs/doc1'), $doc1);
        } else {
            $doc1 = 'not found';
        }

        if ($request->hasFile('doc2')) {
            $fileEXT    = $request->file('doc2')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc2')->getClientOriginalExtension();
            $doc2       = $filename . '_' . time() . '.' . $EXT;
            $path2      = $request->file('doc2')->move(public_path('file/certivhs/doc2'), $doc2);
        } else {
            $doc2 = 'not found';
        }

        if ($request->hasFile('doc3')) {
            $fileEXT    = $request->file('doc3')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc3')->getClientOriginalExtension();
            $doc3       = $filename . '_' . time() . '.' . $EXT;
            $path3      = $request->file('doc3')->move(public_path('file/certivhs/doc3'), $doc3);
        } else {
            $doc3 = 'not found';
        }

        try {
            $data = Vhs_certi::create([
                'user_id'   => $request->user_id,
                'doc1'      => $doc1,
                'doc2'      => $doc2,
                'doc3'      => $doc3,
            ]);
            $tokenUser = DB::table('users')
                ->where('id', $request->user_id)
                ->where('token', '!=', "")
                ->pluck('token')->toArray();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
                    ->notification([
                        'title' => 'Hai, ada Sertifikat 1VHS baru nih buat kamu!',
                        'body' => $request->title ?? 'Silahkan buka menu sertifikat',
                    ])
                    ->data([
                        'title' => 'Hai, ada Sertifikat 1VHS baru nih buat kamu!',
                        'body' => $request->title ?? 'Silahkan buka menu sertifikat',
                    ])
                    ->send();
            }
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Menambahkan Sertifikat 1VHS'
            ]);
            return response()->json(
                [
                    'data' => "saved successfully " . $data
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * sebuah function bernama show yang menerima parameter $id. Function ini akan menjalankan sebuah query ke database untuk mengambil data dari tabel vhs_certis dan users yang dijoin berdasarkan kolom id dari tabel users. Kemudian, data yang dihasilkan akan dipilih berdasarkan kolom id dari tabel vhs_certis yang sesuai dengan parameter $id yang diterima function. Hasilnya akan disimpan ke dalam variabel $data. Kemudian, function ini akan mengembalikan sebuah respon berupa data yang telah ditampung di dalam variabel $data beserta pesan "success".Jika terjadi exception atau error saat menjalankan query ke database, maka function ini akan mengembalikan respon berupa pesan error yang terjadi.
     */
    public function show($id)
    {
        try {
            $data = DB::table('vhs_certis')
                ->join('users', 'users.id', '=', 'vhs_certis.user_id')
                ->select('vhs_certis.*', 'users.id as idUser', 'users.name as usersname')
                ->where('vhs_certis.id', $id)
                ->first();
            return response()->json([
                'Message'   => 'success',
                'Data'      => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
     * sebuah function bernama update yang menerima dua parameter yaitu $request dan $id. Pertama-tama, function ini akan memvalidasi input yang diterima dari $request dengan menggunakan class Validator dari Laravel. Jika validasi input gagal, maka function akan mengembalikan respon berupa pesan error.

     * Jika validasi input berhasil, maka function akan memeriksa apakah $request memiliki file dengan key doc1. Jika ada, maka file tersebut akan diupload ke direktori public/file/certivhs/doc1 dengan nama file yang sudah dienkripsi dengan menambahkan timestamp pada nama aslinya. Kemudian, function akan mengupdate data di dalam tabel vhs_certis dengan mengubah nilai kolom doc1 sesuai dengan nama file yang baru diupload.

     * Function juga akan melakukan hal yang sama untuk file dengan key doc2 dan doc3, dengan mengunggah file ke direktori yang sesuai dan mengupdate nilai kolom doc2 atau doc3 di dalam tabel vhs_certis sesuai dengan nama file yang baru diupload.

     * Jika $request tidak memiliki file dengan key doc1, doc2, atau doc3, maka function akan mengecek apakah $request memiliki input dengan key user_id. Jika ada, maka function akan mengupdate data di dalam tabel vhs_certis dengan mengubah nilai kolom user_id sesuai dengan input yang diterima. Selain itu, function juga akan mengirim notifikasi ke user yang bersangkutan dengan menggunakan FCM (Firebase Cloud Messaging).

     * Jika tidak ada input dengan key user_id, maka function akan mengembalikan respon berupa pesan "Nothing to update".
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        if ($request->hasFile('doc1')) {
            $fileEXT    = $request->file('doc1')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc1')->getClientOriginalExtension();
            $doc1       = $filename . '_' . time() . '.' . $EXT;
            $path1      = $request->file('doc1')->move(public_path('file/certivhs/doc1'), $doc1);

            $data = Vhs_certi::findOrFail($id)->update([
                'user_id'   => $request->user_id,
                'doc1'      => $doc1,
            ]);
        }

        if ($request->hasFile('doc2')) {
            $fileEXT    = $request->file('doc2')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc2')->getClientOriginalExtension();
            $doc2       = $filename . '_' . time() . '.' . $EXT;
            $path2      = $request->file('doc2')->move(public_path('file/certivhs/doc2'), $doc2);

            $data = Vhs_certi::findOrFail($id)->update([
                'user_id'   => $request->user_id,
                'doc2'      => $doc2,
            ]);
        }

        if ($request->hasFile('doc3')) {
            $fileEXT    = $request->file('doc3')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc3')->getClientOriginalExtension();
            $doc3       = $filename . '_' . time() . '.' . $EXT;
            $path3      = $request->file('doc3')->move(public_path('file/certivhs/doc3'), $doc3);

            $data = Vhs_certi::findOrFail($id)->update([
                'user_id'   => $request->user_id,
                'doc3'      => $doc3,
            ]);
        }
        if ($request->user_id) {
            try {
                $data = Vhs_certi::findOrFail($id)->update([
                    'user_id'   => $request->user_id,
                ]);
                $tokenUser = DB::table('users')
                    ->where('id', $request->user_id)
                    ->where('token', '!=', "")
                    ->pluck('token')->toArray();
                if ($tokenUser) {
                    $result = fcm()->to($tokenUser)
                        ->timeToLive(0)
                        ->priority('high')
                        ->notification([
                            'title' => 'Hai, ada Sertifikat 1VHS baru nih buat kamu!',
                            'body' => $request->title ?? 'Silahkan buka menu sertifikat',
                        ])
                        ->data([
                            'title' => 'Hai, ada Sertifikat 1VHS baru nih buat kamu!',
                            'body' => $request->title ?? 'Silahkan buka menu sertifikat',
                        ])
                        ->send();
                }
                DB::table('activities')->insert([
                    'user_id' => auth()->user()->id,
                    'time' => Carbon::now(),
                    'details' => 'Men-update Sertifikat 1VHS'
                ]);
                return response()->json(
                    [
                        'data' => "updated successfully " . $data
                    ]
                );
            } catch (\Throwable $th) {
                return response()->json([
                    'message'   => $th->getMessage(),
                ]);
            }
        } else {
            return response()->json([
                'message'   => "error",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Code di atas adalah sebuah function bernama destroy yang menerima parameter $id. Function ini akan mencari data di dalam tabel vhs_certis yang memiliki kolom id sesuai dengan parameter $id yang diterima. Jika data tersebut ditemukan, maka function akan menghapus data tersebut dari tabel dengan menjalankan method delete() pada objek yang merepresentasikan data tersebut.
     * Jika proses penghapusan berhasil, maka function akan mengembalikan respon berupa pesan "Data Berhasil di Hapus". Jika terjadi error saat menjalankan query ke database, maka function akan mengembalikan respon berupa pesan error yang terjadi.
     */
    public function destroy($id)
    {
        try {
            $delete = Vhs_certi::findOrFail($id);
            $delete->delete();
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Hapus Sertifikat 1VHS'
            ]);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
