<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use Exception;
use File;

class VhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah contoh fungsi index yang tidak menerima parameter. Fungsi ini terlihat seperti mengambil dan mengembalikan data dari tabel atau model Vhs di database.
     * Fungsi ini mengambil semua baris dari tabel atau model Vhs yang diurutkan berdasarkan kolom created_at secara descending (dari yang terbaru). Kemudian, fungsi tersebut mengembalikan respons JSON dengan data yang dihasilkan dari query sebelumnya.
     */
    public function index()
    {
        $data = Vhs::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
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
     * Ini adalah contoh fungsi store yang menerima parameter Request $request di PHP. Fungsi ini terlihat seperti menyimpan data baru ke tabel atau model Vhs di database, serta meng-upload file video dan thumbnail ke server.
     * Pertama, fungsi ini menggunakan class Validator untuk memvalidasi input yang diterima melalui $request. Validasi ini memastikan bahwa kolom title dan description harus diisi, sedangkan file thumbnail dan video hanya boleh berupa file gambar atau file video, masing-masing dengan ukuran maksimum 2MB dan tidak harus diisi. Jika validasi gagal, fungsi tersebut akan mengembalikan respons JSON dengan error yang terjadi.
     * Setelah itu, fungsi tersebut mempersiapkan untuk meng-upload file video dengan menyiapkan variabel-variabel yang diperlukan. Kemudian, fungsi tersebut meng-upload file thumbnail ke server dengan menggunakan Storage::disk('public')->put().
     * Setelah itu, fungsi tersebut membuat objek baru dari model Vhs, mengisi setiap kolom dengan data dari $request, serta menyimpan data tersebut ke database. Kemudian, jika file video juga telah disertakan dalam $request, fungsi tersebut akan mencoba meng-upload file video ke server dengan menggunakan Storage::disk('public')->put(). Jika proses upload berhasil, fungsi tersebut akan mengembalikan respons JSON dengan data yang baru disimpan dan pesan sukses. Jika terjadi error selama proses upload, fungsi tersebut akan mengembalikan respons JSON dengan pesan error yang terjadi.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'image|max:2084|nullable',
            'video' => 'file|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        /* PREPARE VIDEO UPLOAD */
        $video_name = null;
        $video_path = null;
        $video = null;
        if ($request->hasFile('video')) {
            $video = $request->video;
            $video_path = 'files/vhs/video/';
            $video_name = Str::random(20).'.'.$video->getClientOriginalExtension();
        }
        /* END PREPARE VIDEO UPLOAD */

        /* START VIDEO UPLOAD */
        $thumbnail_name = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail_name = Storage::disk('public')->put('files/vhs/thumbnail', $request->thumbnail);
        }
        /* END VIDEO UPLOAD */

        $vhs = new Vhs();
        $vhs->title = $request->title;
        $vhs->description = $request->description;
        $vhs->type = $request->type;
        $vhs->thumbnail = $thumbnail_name;
        $vhs->video = $video_path.$video_name;
        $vhs->save();

        /* START VIDEO UPLOAD */
        if ($request->hasFile('video')) {
            try {
                Storage::disk('public')->put($video_path.$video_name, file_get_contents($video));
            } catch (Exception $e){
                return response()->json(['error'=>$e->getMessage()], 401);
            }
        }
        /* END VIDEO UPLOAD */

        return response()->json(['data' => $vhs, 'message' => 'Data berhasil disimpan!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function show(Vhs $vhs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function edit(Vhs $vhs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vhs $vhs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah contoh fungsi destroy yang menerima parameter $id di PHP. Fungsi ini terlihat seperti menghapus data dari tabel atau model Vhs di database, serta menghapus file video dan thumbnail yang terkait dari server.
     * Pertama, fungsi ini mencari data di tabel atau model Vhs dengan id yang sama dengan $id dan menyimpannya ke dalam variabel $vhs. Kemudian, fungsi tersebut menyiapkan path atau lokasi file video dan thumbnail yang terkait dengan $vhs.
     * Setelah itu, fungsi tersebut menggunakan class File untuk menghapus file video dan thumbnail dari server dengan menggunakan File::delete(). Kemudian, fungsi tersebut menghapus data $vhs dari database dengan menggunakan method delete() pada model Vhs. Terakhir, fungsi tersebut mengembalikan respons JSON dengan pesan sukses.
     */
    public function destroy($id)
    {
        $vhs = Vhs::find($id);
        $video_path = public_path().'/files/'.$vhs->video;
        $thumbnail_path = public_path().'/files/'.$vhs->thumbnail;
        $res = File::delete($video_path, $thumbnail_path);
        $vhs->delete();
        return response()->json(['message' => 'delete successfully']);
    }
}
