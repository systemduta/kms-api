<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalUserVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JadwalUserVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada function index, terdapat sebuah perintah try-catch yang berfungsi untuk menangkap exception yang terjadi pada bagian dalam try. Bagian dalam try terdiri dari sebuah query yang menggunakan fungsi DB::table() untuk mengambil data dari beberapa tabel yang terkait. Kemudian, fungsi join() digunakan untuk menggabungkan data dari beberapa tabel yang terkait dengan tabel jadwal_user_vhs melalui relasi foreign key. Selanjutnya, fungsi select() digunakan untuk memilih kolom-kolom yang ingin ditampilkan pada hasil query, dan fungsi get() digunakan untuk mengeksekusi query tersebut. Setelah itu, hasil query tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json(). Apabila terdapat exception yang terjadi pada bagian dalam try, maka exception tersebut akan ditangkap oleh catch dan hasilnya akan dikembalikan dalam bentuk JSON dengan status HTTP 500 (Internal Server Error).
     */
    public function index()
    {
        try {
            $data = DB::table('jadwal_user_vhs')
                ->join('users','users.id','jadwal_user_vhs.user_id')
                ->join('jadwalvhs','jadwalvhs.id','jadwal_user_vhs.jadwal_id')
                ->join('companies','companies.id','jadwal_user_vhs.company_id')
                ->select('jadwal_user_vhs.*','users.name as namauser','jadwalvhs.name as jadwalvhsname','companies.name as companyname','jadwalvhs.start as start')
                ->get();
            return response()->json(
                [
                    'data' => $data
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    /**
     * Pada function getCompany, terdapat sebuah perintah try-catch yang berfungsi untuk menangkap exception yang terjadi pada bagian dalam try. Bagian dalam try terdiri dari sebuah query yang menggunakan fungsi DB::table() untuk mengambil data dari tabel companies. Kemudian, fungsi whereNotIn() digunakan untuk memilih data yang tidak memiliki nama sesuai dengan daftar nama yang disebutkan di dalam [ ]. Selanjutnya, fungsi select() digunakan untuk memilih kolom-kolom yang ingin ditampilkan pada hasil query, dan fungsi get() digunakan untuk mengeksekusi query tersebut. Setelah itu, hasil query tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json(). Apabila terdapat exception yang terjadi pada bagian dalam try, maka exception tersebut akan ditangkap oleh catch dan hasilnya akan dikembalikan dalam bentuk JSON dengan status HTTP 500 (Internal Server Error).
     */
    public function getCompany() {
        try {
            $data = DB::table('companies')
                    ->whereNotIn('name',[
                        'MAESA HOLDING', 
                        'ANUGERAH UTAMA MOTOR',
                        'BANK ARTHAYA',
                        'CUN MOTOR GROUP',
                        'DUA TANGAN INDONESIA',
                        'ES KRISTAL PMP GROUP',
                        'HENNESY CUISINE',
                        'KOPERASI SDM',
                        'MAESA FOUNDATION',
                        'MAESA HOTEL',
                        'MIXTRA INTI TEKINDO',
                        'PABRIK ES PMP GROUP',
                        'PANDHU DISTRIBUTOR',
                        'PRAMA LOGISTIC',
                        'PT. PUTRA MAESA PERSADA',
                        'Panen Mutiara Pakis',
                        'HENNESSY CUISINE',
                        'WERKST MATERIAL HANDLING',
                        'PT. Prama Madya Parama'
                        ])
                    ->select('id as idCompany','name as nameCompany')
                    ->get();
            return response()->json(
                [
                    'data' => $data
                ]);
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
     * ada function store, terdapat sebuah validasi yang dilakukan terhadap input yang dikirimkan melalui parameter $request dengan menggunakan fungsi Validator::make(). Apabila ada input yang tidak memenuhi validasi yang ditentukan, maka akan dikembalikan pesan error dalam bentuk JSON dengan status HTTP 400 (Bad Request).
     * Setelah itu, terdapat perintah yang mengecek apakah data yang akan disimpan sudah ada di dalam database atau belum dengan menggunakan fungsi count(). Jika data tersebut sudah ada, maka akan dikembalikan pesan error dalam bentuk JSON dengan status HTTP 400 (Bad Request). Jika data tersebut belum ada, maka akan dilakukan proses penyimpanan data ke dalam database dengan menggunakan fungsi DB::beginTransaction() dan DB::commit(). Fungsi DB::beginTransaction() digunakan untuk memulai sebuah transaksi, sedangkan fungsi DB::commit() digunakan untuk menyimpan data ke dalam database apabila tidak terjadi exception pada bagian dalam try. Apabila terdapat exception yang terjadi, maka akan dilakukan proses rollback dengan menggunakan fungsi DB::rollBack(). Setelah itu, terdapat sebuah perintah yang mengirimkan notifikasi melalui FCM (Firebase Cloud Messaging) kepada user yang bersangkutan, dan hasil penyimpanan data dikembalikan dalam bentuk JSON dengan status HTTP 200 (OK).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'jadwal_id'              => 'required',
            'company_id'             => 'required',
            'user_id'             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }
        
        $cekData = DB::table('jadwal_user_vhs')
                    ->where('jadwal_id',$request->jadwal_id)
                    ->where('user_id',$request->user_id)
                    ->where('company_id',$request->company_id)
                    ->count();
        if ($cekData==1) {
            return response()->json(['error' => $validator->errors()],400);
        }
        else{
                try {
                    DB::beginTransaction();
                    $JadwalGetId = DB::table('jadwal_user_vhs')->insertGetId([
                        'jadwal_id'         => $request->jadwal_id,
                        'company_id'        => $request->company_id,
                        'user_id'           => $request->user_id,
                        'is_take'           => 0,
                    ]);

                    DB::commit();

                    $tokenUser = DB::table('users')
                                ->where('id',$request->user_id)
                                ->where('token','!=',"")
                                ->pluck('token')->toArray();
                    if($tokenUser) {
                        $result = fcm()->to($tokenUser)
                        ->timeToLive(0)
                        ->priority('high')
                        ->notification([
                            'title' => 'Hai, ada jadwal 1VHS baru nih buat kamu!',
                            'body' => $request->title ?? 'Silahkan buka menu 1VHS',
                        ])
                        ->data([
                            'title' => 'Hai, ada jadwal 1VHS baru nih buat kamu!',
                            'body' => $request->title ?? null,
                        ])
                        ->send();
                    }
                    
                    return response()->json([
                        'data'      => $JadwalGetId,
                        'message'   => 'Data Berhasil disimpan!'
                    ],200);
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw new HttpException(500, $exception->getMessage(), $exception);
                }

            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada function show, terdapat sebuah perintah try-catch yang berfungsi untuk menangkap exception yang terjadi pada bagian dalam try. Bagian dalam try terdiri dari sebuah query yang menggunakan fungsi DB::table() untuk mengambil data dari beberapa tabel yang terkait. Kemudian, fungsi join() digunakan untuk menggabungkan data dari beberapa tabel yang terkait dengan tabel jadwal_user_vhs melalui relasi foreign key. Selanjutnya, fungsi select() digunakan untuk memilih kolom-kolom yang ingin ditampilkan pada hasil query, dan fungsi where() digunakan untuk memfilter data berdasarkan kondisi yang ditentukan. Kemudian, fungsi first() digunakan untuk mengambil satu baris data yang sesuai dengan kondisi tersebut. Setelah itu, hasil query tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json(). Apabila terdapat exception yang terjadi pada bagian dalam try, maka exception tersebut akan ditangkap oleh catch dan hasilnya akan dikembalikan dalam bentuk JSON dengan status HTTP 500 (Internal Server Error).
     */
    public function show($id)
    {
        try {
            $data = DB::table('jadwal_user_vhs')
                ->join('users','users.id','jadwal_user_vhs.user_id')
                ->join('jadwalvhs','jadwalvhs.id','jadwal_user_vhs.jadwal_id')
                ->join('companies','companies.id','jadwal_user_vhs.company_id')
                ->select('jadwal_user_vhs.*','users.name as namauser','jadwalvhs.name as jadwalvhsname','companies.name as companyname','jadwalvhs.start as start')
                ->where('jadwal_user_vhs.id',$id)
                ->first();
            return response()->json(
                [
                    'data' => $data
                ]);
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
     * Pada function update, terdapat sebuah validasi yang dilakukan terhadap input yang dikirimkan melalui parameter $request dengan menggunakan fungsi Validator::make(). Apabila ada input yang tidak memenuhi validasi yang ditentukan, maka akan dikembalikan pesan error dalam bentuk JSON dengan status HTTP 400 (Bad Request).
     * Setelah itu, terdapat sebuah perintah yang mencari data yang akan diupdate berdasarkan id yang dikirimkan melalui parameter $id dengan menggunakan fungsi JadwalUserVhs::findOrfail(). Kemudian, data tersebut diupdate dengan menggunakan fungsi update() dan mengubah nilai dari beberapa kolom sesuai dengan input yang diterima melalui parameter $request. Setelah itu, hasil update tersebut dikembalikan dalam bentuk JSON dengan status HTTP 200 (OK). Apabila terdapat exception yang terjadi pada bagian dalam try, maka exception tersebut akan ditangkap oleh catch dan akan dilakukan proses rollback dengan menggunakan fungsi DB::rollBack().
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'jadwal_id'              => 'required',
            'company_id'             => 'required',
            'user_id'             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        try {
            $JadwalGetId=JadwalUserVhs::findOrfail($id)->update([
                'jadwal_id'         => $request->jadwal_id,
                'company_id'        => $request->company_id,
                'user_id'           => $request->user_id,
                'is_take'           => 0,
            ]);
            return response()->json([
                'success'=>$JadwalGetId,
                'message'=>'update successfully'],200);
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
     * Pada function destroy, terdapat sebuah perintah yang mencari data yang akan dihapus berdasarkan id yang dikirimkan melalui parameter $id dengan menggunakan fungsi JadwalUserVhs::find(). Kemudian, apabila data tersebut ditemukan, maka data tersebut akan dihapus dari database dengan menggunakan fungsi JadwalUserVhs::destroy(). Setelah itu, akan dikembalikan pesan sukses dalam bentuk JSON dengan status HTTP 200 (OK). Jika data tersebut tidak ditemukan, maka akan dikembalikan pesan error dalam bentuk JSON dengan status HTTP 404 (Not Found).
     */
    public function destroy($id)
    {
        $idDestroy =JadwalUserVhs::find($id);
        if ($idDestroy) {
            JadwalUserVhs::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } else {
            return response()->json(['error' => "data not delete yet"], 404);
        }
    }
}
