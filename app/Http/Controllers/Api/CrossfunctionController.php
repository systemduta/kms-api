<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crossfunction;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;


class CrossfunctionController extends Controller
{
    public $successStatus = 200; //variabel ini akan dipanggil ketika proses sukses dieksekusi
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama cfdown() yang menerima parameter $id. Fungsi ini digunakan untuk mengambil data dari sebuah tabel crossfunctions yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat sebuah query yang mengambil kolom file dari tabel crossfunctions yang memiliki id yang sama dengan parameter $id dan disimpan pada variabel $sop. Kemudian, fungsi akan mengembalikan sebuah objek JSON yang berisi data file yang telah diambil dari tabel crossfunctions.
     */
    public function cfdown($id)
    {
        $sop = DB::table('crossfunctions')->select('file')->where('id', $id)->first();
        return response()->json(['data' => $sop->file]);
    }

    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama index() yang tidak menerima parameter apapun. Fungsi ini digunakan untuk mengambil semua data dari tabel crossfunctions dengan relasi dengan tabel company, tabel organization, dan tabel sop.
     * Pertama, terdapat sebuah variabel yang di-assign dengan objek User yang sedang login saat ini dengan menggunakan method auth()->user(). Kemudian, terdapat sebuah query yang mengambil semua data dari tabel crossfunctions dengan relasi dengan tabel company, tabel organization, dan tabel sop dan disimpan pada variabel $lampiran. Query tersebut juga mengelompokkan data berdasarkan company_id yang sama dengan company_id dari objek User yang sedang login saat ini jika role dari objek tersebut tidak sama dengan 1. Kemudian, data tersebut diurutkan berdasarkan id dengan urutan descending.
     * Setelah proses pengambilan data selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi data yang telah diambil dari tabel crossfunctions.
     */
    public function index()
    {
        $auth       = auth()->user();
        $cek = DB::table('permissions')->where('user_id', $auth->id)->where('isSuperAdmin', 1)->first();

        // $lampiran   = Crossfunction::with(['company', 'organization', 'sop'])
        //     ->when($auth->role != 1, function ($q) use ($auth) {
        //         return $q->where('company_id', $auth->company_id);
        //     })
        //     ->orderBy('id', 'DESC')
        //     ->get();
        
        // return response()->json(['data' => $lampiran]);

        $query = Crossfunction::with(['company', 'organization', 'sop'])
                ->orderBy('id','DESC');
        
        if (!$cek) {
            $query->where('company_id',$auth->company_id);
        }
        return response()->json(['data' => $query->get()]);

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
     * Pada kode di atas, terdapat sebuah fungsi yang bernama status() yang menerima parameter $id. Fungsi ini digunakan untuk mengubah status dari sebuah data pada tabel crossfunctions yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat sebuah query yang mengambil sebuah data dari tabel crossfunctions yang memiliki id yang sama dengan parameter $id dan disimpan pada variabel $data. Kemudian, terdapat sebuah variabel yang di-assign dengan nilai status dari data tersebut.
     * Jika status saat ini bernilai 1, maka status akan diubah menjadi 2. Sebaliknya, jika status saat ini bernilai 2, maka status akan diubah menjadi 1. Setelah proses perubahan status selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi pesan 'Data Update Successfully'.
     */
    public function status($id)
    {
        $data = Crossfunction::where('id', $id)->first();
        // dd($data->title);

        $st_sekarang = $data->status;

        if ($st_sekarang == 1) {
            $sop = Crossfunction::find($id);
            $sop->status = 2;
            $sop->save();
        } else {
            $sop = Crossfunction::find($id);
            $sop->status = 1;
            $sop->save();
        }

        return response()->json(['message' => 'Data Update Successfully'], $this->successStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama store() yang menerima sebuah objek Request. Fungsi ini digunakan untuk menyimpan data baru pada tabel crossfunctions.
     * Pertama, terdapat sebuah proses validasi input yang dilakukan dengan menggunakan class Validator. Jika terdapat input yang tidak sesuai dengan validasi yang ditentukan, maka fungsi akan mengembalikan sebuah objek JSON yang berisi pesan error.
     * Kemudian, terdapat sebuah variabel yang di-assign dengan objek User yang sedang login saat ini dengan menggunakan method auth()->user(). Selanjutnya, terdapat proses upload file yang disimpan pada public folder dengan nama file yang diberikan secara random.
     * Setelah proses upload file selesai, terdapat sebuah proses penyimpanan data pada tabel crossfunctions dengan menggunakan method insertGetId(). Setelah proses penyimpanan data selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi data yang telah disimpan dan pesan 'Data Berhasil disimpan!'.
     */
    public function store(Request $request)
    {
        // error_reporting(0);
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'sop_id'              => 'required',
            'file'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $auth   = auth()->user();

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',') + 1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'sop_' . Str::random(10) . '.' . $ext;
        Storage::disk('public')->put('files/' . $filename, base64_decode($file));
        /* END FILE UPLOAD */

        try {
            DB::beginTransaction();
            $lampiranGetId = DB::table('crossfunctions')->insertGetId([
                'company_id'        => $auth->company_id,
                'name'              => $request->name,
                'sop_id'            => $request->sop_id,
                'file'              => 'files/' . $filename,
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
     * Pada kode di atas, terdapat sebuah fungsi yang bernama show() yang menerima parameter $id. Fungsi ini digunakan untuk menampilkan data dari tabel crossfunctions yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat sebuah query yang mengambil sebuah data dari tabel crossfunctions yang memiliki id yang sama dengan parameter $id dan disimpan pada variabel $data. Kemudian, fungsi akan mengembalikan sebuah objek JSON yang berisi data yang telah diambil dari tabel crossfunctions.
     */
    public function show($id)
    {
        $data = DB::table('crossfunctions')->where('id', $id)->first();
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
    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama update() yang menerima sebuah objek Request dan parameter $id. Fungsi ini digunakan untuk memperbarui data pada tabel crossfunctions yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat sebuah variabel yang di-assign dengan nilai dari input name dan sop_id. Kemudian, terdapat proses upload file yang disimpan pada public folder dengan nama file yang diberikan secara random.
     * Setelah proses upload file selesai, terdapat sebuah proses pembaruan data pada tabel crossfunctions dengan menggunakan method find() dan save(). Setelah proses pembaruan data selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi data yang telah diperbarui dan pesan 'update successfully'.
     */
    public function update(Request $request, $id)
    {
        $title = $request->name;
        $description = $request->sop_id;

        $lampiran               = Crossfunction::find($id);
        if ($request->has('file')) {
            /* START FILE UPLOAD */
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',') + 1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = 'Crossfunction_' . Str::random(10) . '.' . $ext;
            Storage::disk('public')->put('files/' . $filename, base64_decode($file));

            $lampiran->file         = 'files/' . $filename;
            /* END FILE UPLOAD */
        }

        $lampiran->name         = $title;
        $lampiran->sop_id       = $description;
        $lampiran->save();

        return response()->json(
            [
                'success' => $lampiran,
                'message' => 'update successfully'
            ],
            $this->successStatus
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama destroy() yang menerima parameter $id. Fungsi ini digunakan untuk menghapus data dari tabel crossfunctions yang memiliki id yang sama dengan parameter $id.
     * Untuk menghapus data tersebut, terdapat sebuah proses penggunaan method destroy() dari class Crossfunction. Setelah proses penghapusan data selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi pesan 'Data Berhasil di Hapus'.
     */
    public function destroy($id)
    {
        Crossfunction::destroy($id);
        return response()->json([
            'message' => 'Data Berhasil di Hapus'
        ]);
    }
}
