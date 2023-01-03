<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LampiranController extends Controller
{
    public $successStatus = 200; //variable yang akan di panggil saat operasi sukses dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Fungsi lampdown ini digunakan untuk mengambil nama file lampiran dari tabel lampirans di database berdasarkan ID yang diberikan dan melakukan download di bagian frontend.
     * Pertama-tama, fungsi ini akan mengambil nama file dari tabel lampirans dengan menggunakan query SELECT dan menambahkan syarat untuk hanya mengambil data yang memiliki ID yang sama dengan yang diberikan. Kemudian, nama file tersebut akan dikembalikan sebagai respon dalam bentuk JSON.
     */
    public function lampdown($id)
    {
        $sop = DB::table('lampirans')->select('file')->where('id',$id)->first();
        return response()->json(['data' => $sop->file]);
    }

    /**
     * Fungsi index ini digunakan untuk menampilkan data lampiran yang tersimpan di dalam tabel lampirans.
     * Pertama-tama, variabel $auth akan diisi dengan objek user yang sedang login. Kemudian, fungsi ini akan mengambil seluruh data lampiran dari tabel lampirans dengan menggunakan fungsi with untuk mengambil data relasi dari tabel lain yang terkait (seperti company, organization, dan sop).
     * Jika role dari user yang sedang login bukan 1 (artinya bukan administrator), maka akan ditambahkan syarat untuk hanya menampilkan data lampiran yang memiliki company_id yang sama dengan user yang sedang login.
     * Setelah itu, data yang diambil akan diurutkan berdasarkan ID dari yang terbaru ke yang terlama, dan dikembalikan sebagai respon dalam bentuk JSON.
     */
    public function index()
    {
        $auth       = auth()->user();
        $lampiran   = Lampiran::with(['company','organization','sop'])
                    ->when($auth->role!=1, function ($q) use ($auth) {
                        return $q->where('company_id', $auth->company_id);
                    })
                    ->orderBy('id', 'DESC')
                    ->get();
        return response()->json(['data' => $lampiran]);
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
     * Fungsi status ini digunakan untuk mengubah status dari sebuah data lampiran yang ada di tabel lampirans.
     * Pertama-tama, fungsi ini akan mengambil data lampiran yang memiliki ID yang sama dengan yang diberikan dengan menggunakan query SELECT. Kemudian, variabel $st_sekarang akan diisi dengan status saat ini dari data lampiran tersebut.
     * Jika $st_sekarang bernilai 1, maka status akan diubah menjadi 2. Sebaliknya, jika $st_sekarang bernilai 2, maka status akan diubah menjadi 1. Setelah perubahan status selesai dilakukan, maka akan dikembalikan respon berupa pesan "Data Update Successfully" dengan status yang sesuai.
     */
    public function status($id)
    {
        $data = Lampiran::where('id',$id)->first();
        // dd($data->title);

        $st_sekarang = $data->status;

        if ($st_sekarang == 1) {
            $sop = Lampiran::find($id);
            $sop->status = 2;
            $sop->save();
        }else{
            $sop = Lampiran::find($id);
            $sop->status = 1;
            $sop->save();
        }

        return response()->json(['message' => 'Data Update Successfully'],$this->successStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Fungsi store ini digunakan untuk menyimpan data lampiran baru ke dalam tabel lampirans.
     * Pertama-tama, fungsi ini akan memvalidasi input yang diberikan dengan menggunakan Validator. Jika terdapat kesalahan dalam input, maka akan dikembalikan pesan error yang terjadi.
     * Kemudian, file yang diberikan akan di-upload ke dalam storage dengan nama file yang diacak. Selanjutnya, data lampiran baru akan disimpan ke dalam tabel lampirans dengan menggunakan query INSERT dan mengambil ID yang baru saja disimpan dengan menggunakan fungsi insertGetId.
     * Jika proses penyimpanan data berhasil, maka akan dikembalikan respon berupa ID data lampiran yang baru disimpan dan pesan "Data Berhasil disimpan!". Sebaliknya, jika terjadi kesalahan, maka akan dikembalikan pesan error yang terjadi.
     */
    public function store(Request $request)
    {
        // error_reporting(0);
        $validator = Validator::make($request->all(),[
            'name'              => 'required',
            'file'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        $auth   = auth()->user();

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'sop_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        try {
            DB::beginTransaction();
            $lampiranGetId = DB::table('lampirans')->insertGetId([
                'company_id'        => $auth->company_id,
                'name'              => $request->name,
                'sop_id'            => $request->sop_id,
                'file'              => 'files/'.$filename,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $lampiranGetId,
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
     * Fungsi show ini digunakan untuk menampilkan data lampiran yang memiliki ID yang sama dengan yang diberikan.
     * Fungsi ini akan mengambil data lampiran dari tabel lampirans dengan menggunakan query SELECT dan menambahkan syarat untuk hanya menampilkan data yang memiliki ID yang sama dengan yang diberikan. Kemudian, data yang diambil akan dikembalikan sebagai respon dalam bentuk JSON.
     */
    public function show($id)
    {
        $data = DB::table('lampirans')->where('id',$id)->first();
        return response()->json(['success' => $data], $this->successStatus);
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
    /**Fungsi update ini digunakan untuk mengupdate data lampiran yang memiliki ID yang sama dengan yang diberikan.

    Fungsi ini menerima parameter Request yang berisi data yang akan diupdate dan ID dari data lampiran yang akan diupdate. Kemudian, fungsi ini akan melakukan validasi terhadap data yang diterima dengan menggunakan class Validator. Jika validasi gagal, fungsi akan mengembalikan respon dengan menampilkan error yang terjadi.

    Jika validasi berhasil, fungsi akan melakukan upload file yang diterima dari parameter Request ke dalam storage. Kemudian, fungsi akan mencari data lampiran yang akan diupdate dengan menggunakan query SELECT dan mengupdate data tersebut dengan data yang diterima dari parameter Request. Setelah itu, fungsi akan menyimpan perubahan data tersebut ke dalam database dan mengembalikan respon berupa data yang telah diupdate dan pesan bahwa proses update berhasil. */
    public function update(Request $request, $id)
    {
        $title = $request->name;
        $description = $request->sop_id;
        $image = '';

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'sop_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'sop_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
        }

        $lampiran               = Lampiran::find($id);
        $lampiran->name         = $title;
        $lampiran->sop_id       = $description;
        $lampiran->image        = $imgName;
        $lampiran->file         = 'files/'.$filename;
        $lampiran->save();


        // DB::commit();
        return response()->json([
            'success'=>$lampiran,
            'message'=>'update successfully'],
        $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah method yang digunakan untuk menghapus data lampiran dengan menggunakan id yang diberikan. Method ini akan menghapus data yang ditentukan dari database dan mengembalikan respon dengan pesan "Data Berhasil di Hapus" sebagai konfirmasi bahwa data telah berhasil dihapus.
     * Method ini menggunakan class Lampiran untuk mengakses method destroy(). Method destroy() adalah method yang tersedia pada class model Lampiran yang dapat digunakan untuk menghapus data dari database.
     * Method ini juga menggunakan respon dari helper response() dengan method json() untuk mengembalikan respon dalam format JSON ke client.
     */
    public function destroy($id)
    {
        Lampiran::destroy($id);
        return response()->json([
            'message' => 'Data Berhasil di Hapus'
        ]);
    }
}
