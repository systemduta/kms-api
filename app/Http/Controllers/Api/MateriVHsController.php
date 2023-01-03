<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;
use Symfony\Component\HttpKernel\Exception\HttpException;


class MateriVHsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
      * Ini adalah sebuah fungsi yang disebut downloadfile yang menerima satu parameter, yaitu $id. Fungsi ini digunakan untuk mengunduh file dari database. Pertama-tama, fungsi ini mengambil data file dari tabel materi_vhs di database dengan menggunakan query builder dari Laravel. Query ini akan mengambil field file dari baris di tabel materi_vhs yang memiliki id sesuai dengan parameter $id yang diberikan. Kemudian, fungsi ini akan mengembalikan respons dengan data file tersebut dalam bentuk JSON.
      */
    public function downloadfile($id)
    {
        $materi = DB::table('materi_vhs')->select('file')->where('id',$id)->first();
        return response()->json(['data' => $materi->file]);
    }

    /**
     * Ini adalah sebuah fungsi yang disebut index yang tidak menerima parameter apapun. Fungsi ini digunakan untuk menampilkan daftar data materi dari tabel materi_vhs di database. Pertama-tama, fungsi ini akan melakukan join dengan tabel jadwalvhs dengan menggunakan closure. Kemudian, fungsi ini akan mengambil semua data dari tabel materi_vhs dan tabel jadwalvhs yang telah dijoin tadi, dan mengambil field name dari tabel jadwalvhs dengan alias jadwal_vhs_name. Setelah itu, data yang didapat akan diurutkan berdasarkan field id dari tabel materi_vhs secara descending (dari yang terbesar ke yang terkecil). Terakhir, fungsi ini akan mengembalikan respons dengan data yang didapat dalam bentuk JSON. Jika terjadi exception atau error pada saat menjalankan query, fungsi ini akan mengembalikan respons dengan pesan error yang terjadi.
     */
    public function index()
    {
        try {
            $data=MateriVhs::join('jadwalvhs',function($join){
                $join->on('materi_vhs.jadwal_id','=','jadwalvhs.id');
            })->select('materi_vhs.*','jadwalvhs.name as jadwal_vhs_name')->orderBy('materi_vhs.id','desc')->get();
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
     * Ini adalah sebuah fungsi yang disebut store yang menerima sebuah parameter berupa Request. Fungsi ini digunakan untuk menambahkan data baru ke tabel materi_vhs di database. Pertama-tama, fungsi ini akan memvalidasi input yang diberikan dengan menggunakan validator dari Laravel. Kemudian, fungsi ini akan melakukan proses upload untuk tiga jenis file yang mungkin diberikan, yaitu image, file, dan video. Untuk setiap jenis file, fungsi ini akan mengecek apakah file tersebut ada di request. Jika ada, maka file tersebut akan diupload ke server dengan nama file yang unik (dibuat dengan menambahkan waktu pada nama file asli). Jika tidak ada file tersebut di request, maka akan disimpan nilai "error" sebagai placeholder.
     * Setelah semua file selesai diupload atau diproses, fungsi ini akan menambahkan data baru ke tabel materi_vhs dengan menggunakan model MateriVhs. Data yang ditambahkan terdiri dari field name, desc, type, jadwal_id, image, file, dan video, yang masing-masing diisi dengan input yang diberikan dari request. Setelah data berhasil ditambahkan, fungsi ini akan mengembalikan respons dengan pesan yang menyatakan bahwa data telah berhasil disimpan. Jika terjadi exception atau error pada saat menjalankan query, fungsi ini akan mengembalikan respons dengan pesan error yang terjadi.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'type' => 'required',
            'jadwal_id' => 'required',
            'image' => 'image|max:2084|nullable',
            'file' => 'file|nullable',
            'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
                
        /* PREPARE image UPLOAD */
        if ($request->hasFile('image')) {
            $imageEXT    = $request->file('image')->getClientOriginalName();
            $filename   = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('image')->getClientOriginalExtension();
            $fileimage = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('image')->move(public_path('file/materivhs/image'), $fileimage);
        }else {
            $fileimage = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE file UPLOAD */
        if ($request->hasFile('file')) {
            $fileEXT    = $request->file('file')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('file')->getClientOriginalExtension();
            $fileUp     = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('file')->move(public_path('file/materivhs/file'), $fileUp);
        }else {
            $fileUp = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE video UPLOAD */
        if ($request->hasFile('video')) {
            $videoEXT       = $request->file('video')->getClientOriginalName();
            $filename       = pathinfo($videoEXT, PATHINFO_FILENAME);
            $EXT            = $request->file('video')->getClientOriginalExtension();
            $fileVideo      = $filename. '_'.time().'.' .$EXT;
            $path           = $request->file('video')->move(public_path('file/materivhs/video'), $fileVideo);
        }else {
            $fileVideo = 'error';
        }        
        /* END image UPLOAD */
        // dd($fileimage);
        // dd($fileUp);
        // dd($fileVideo);
        try {
            $data= MateriVhs::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'jadwal_id' => $request->jadwal_id,
                'image' => $fileimage,
                'file' => $fileUp,
                'video' => $fileVideo,
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
     * Ini adalah sebuah fungsi yang disebut show yang menerima satu parameter, yaitu $id. Fungsi ini digunakan untuk menampilkan detail data dari suatu materi di tabel materi_vhs di database. Pertama-tama, fungsi ini akan melakukan join dengan tabel jadwalvhs dengan menggunakan closure. Kemudian, fungsi ini akan mengambil data dari tabel materi_vhs dan tabel jadwalvhs yang telah dijoin tadi, dan mengambil field name dari tabel jadwalvhs dengan alias jadwal_vhs_name. Data yang didapat akan di filter berdasarkan id dari tabel materi_vhs sesuai dengan parameter $id yang diberikan. Kemudian, data yang didapat akan diubah menjadi bentuk array assosiatif yang kemudian akan dijadikan sebagai elemen dari array success. Terakhir, fungsi ini akan mengembalikan respons dengan data yang didapat dalam bentuk JSON. Jika terjadi exception atau error pada saat menjalankan query, fungsi ini akan mengembalikan respons dengan pesan error yang terjadi.
     */
    public function show($id)
    {
        try {
            $data=MateriVhs::join('jadwalvhs',function($join){
                $join->on('materi_vhs.jadwal_id','=','jadwalvhs.id');
            })
                ->select('materi_vhs.*','jadwalvhs.name as jadwal_vhs_name')
                ->where('materi_vhs.id',$id)
                ->first();
            return response()->json(
                [
                    'success' => $data
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
     * Fungsi update ini digunakan untuk mengupdate data dari sebuah tabel di database.
     * Pertama-tama, fungsi ini akan memeriksa apakah ada file yang diupload dari formulir dengan nama image. Jika ada, maka file tersebut akan di-rename dengan menambahkan waktu saat ini pada nama filenya dan diupload ke dalam folder file/materivhs/image. Kemudian, data file yang baru diupload tersebut akan disimpan ke dalam tabel materivhs di database.
     * Proses yang sama juga dilakukan untuk file dengan nama file dan video, dengan menggunakan folder yang berbeda sesuai dengan nama file tersebut.
     * Setelah semua file yang diupload telah disimpan ke dalam database, maka data lainnya yang diinput dari formulir, seperti nama, deskripsi, tipe, dan ID jadwal, juga akan disimpan ke dalam tabel materivhs di database.
     * Jika tidak terjadi error, maka akan dikembalikan pesan bahwa data telah tersimpan dengan sukses. Namun jika terjadi error, maka akan dikembalikan pesan error yang spesifik.
     */
    public function update(Request $request, $id)
    {                
        /* PREPARE image UPLOAD */
        if ($request->hasFile('image')) {
            $imageEXT    = $request->file('image')->getClientOriginalName();
            $filename   = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('image')->getClientOriginalExtension();
            $fileimage = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('image')->move(public_path('file/materivhs/image'), $fileimage);

            $updateimage= MateriVhs::findOrfail($id)->update([
                'image' => $fileimage,
            ]);
        }else {
            $fileimage = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE file UPLOAD */
        if ($request->hasFile('file')) {
            $fileEXT    = $request->file('file')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('file')->getClientOriginalExtension();
            $fileUp     = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('file')->move(public_path('file/materivhs/file'), $fileUp);

            $updatefile= MateriVhs::findOrfail($id)->update([
                'file' => $fileUp,
            ]);
        }else {
            $fileUp = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE video UPLOAD */
        if ($request->hasFile('video')) {
            $videoEXT       = $request->file('video')->getClientOriginalName();
            $filename       = pathinfo($videoEXT, PATHINFO_FILENAME);
            $EXT            = $request->file('video')->getClientOriginalExtension();
            $fileVideo      = $filename. '_'.time().'.' .$EXT;
            $path           = $request->file('video')->move(public_path('file/materivhs/video'), $fileVideo);

            $updatevideo= MateriVhs::findOrfail($id)->update([
                'video' => $fileVideo,
            ]);
        }else {
            $fileVideo = 'error';
        }        
        /* END image UPLOAD */
       
        try {
            $data= MateriVhs::findOrfail($id)->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'jadwal_id' => $request->jadwal_id,
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
     * Fungsi destroy ini digunakan untuk menghapus data dari sebuah tabel di database, serta file yang terkait dengan data tersebut.
     * Pertama-tama, fungsi akan mencari data yang akan dihapus berdasarkan ID yang diberikan. Kemudian, fungsi akan mengecek apakah file yang terkait dengan data tersebut ada di dalam folder yang sesuai dengan nama filenya. Jika ada, maka file tersebut akan dihapus dari folder tersebut.
     * Setelah itu, data tersebut akan dihapus secara permanen dari tabel materivhs di database. Jika tidak terjadi error, maka akan dikembalikan pesan bahwa data telah terhapus dengan sukses. Namun jika terjadi error, maka akan dikembalikan pesan error yang spesifik.
     */
    public function destroy($id)
    {
        try {
            $delete = MateriVhs::findOrFail($id);
            $pathfile = app_path("file/materivhs/file/{$delete->file}");
            $pathImage = app_path("file/materivhs/image/{$delete->image}");
            $pathVideo = app_path("file/materivhs/video/{$delete->video}");
            if(File::exists($pathfile) || File::exists($pathImage) || File::exists($pathVideo)){
                unlink($pathfile);
                unlink($pathImage);
                unlink($pathVideo);
            };
            $delete->forceDelete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
