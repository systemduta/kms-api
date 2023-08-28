<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZoomsVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpKernel\Exception\HttpException;

class ZoomController extends Controller
{

    public $successStatus = 200; //jika data sukses di kirim maka akan ada response code 200
    public $errorStatus = 403;    //jika data gagal dikirim maka akan ada response code 403
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Ini adalah sebuah method bernama index yang mengembalikan sebuah response dalam bentuk JSON. Method ini akan mengambil data dari tabel zooms_vhs dan jadwalvhs di database, menyatukannya, dan mengurutkannya berdasarkan id dari tabel zooms_vhs dari yang terbesar ke yang terkecil. Kemudian, data tersebut akan dikembalikan dalam bentuk JSON dengan key data. Jika terjadi exception (error), maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP 500 (Internal Server Error).
     */
    public function index()
    {
        try {
            $data = DB::table('zooms_vhs')
                ->join('jadwalvhs', 'jadwalvhs.id', 'zooms_vhs.jadwal_id')
                ->select('zooms_vhs.id as zoom_id', 'jadwalvhs.id as jadwalvhs_id', 'zooms_vhs.name as zoom_name', 'jadwalvhs.name as jadwalvhs_name', 'zooms_vhs.*', 'jadwalvhs.*')
                ->orderBy('zooms_vhs.id', 'desc')
                ->get();
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
     * Ini adalah sebuah method bernama getvhs yang mengembalikan sebuah response dalam bentuk JSON. Method ini akan mengambil data dari tabel jadwalvhs di database, dan hanya akan mengembalikan data yang memiliki id sesuai dengan id company dari user yang sedang login, jika user tersebut memiliki role bukan 1 (admin) dan memiliki company_id. Kemudian, data tersebut akan dikembalikan dalam bentuk JSON dengan key data. Jika terjadi exception (error), maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP 500 (Internal Server Error).
     */

    public function getvhs()
    {
        try {
            // $user = auth()->user();  //memperoleh data user login
            $data = DB::table('jadwalvhs')
                ->select('id', 'name', 'batch', 'start')
                // ->when(($user && $user->role!=1), function ($q) use ($user) {
                //         return $q->where('id', $user->company_id);
                //     })
                ->get();
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama store yang menerima sebuah request (data yang dikirim dari client) dan mengembalikan sebuah response dalam bentuk JSON. Method ini akan memvalidasi input yang dikirim dari client dengan menggunakan Validator class. Jika input tidak valid, maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP 400 (Bad Request). Jika input valid, maka akan dilakukan proses penyimpanan data ke tabel zooms_vhs di database. Jika proses penyimpanan data berhasil, maka akan dikembalikan response dalam bentuk JSON dengan key data dan message serta status kode HTTP yang sesuai dengan $this->successStatus. Jika terjadi exception (error) selama proses penyimpanan data, maka akan dilakukan rollback (pembatalan) terhadap seluruh proses yang telah dilakukan sebelumnya dan akan dikembalikan response dalam bentuk HttpException dengan status kode HTTP 500 (Internal Server Error).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id'             => 'required',
            'name'                  => 'required',
            'times'                 => 'required',
            'link'                  => 'required',
            'meeting_id'            => 'required',
            'password'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        try {
            DB::beginTransaction();
            $JadwalGetId = DB::table('zooms_vhs')->insertGetId([
                'jadwal_id'         => $request->jadwal_id,
                'name'              => $request->name,
                'times'             => $request->times,
                'link'              => $request->link,
                'meeting_id'        => $request->meeting_id,
                'password'          => $request->password,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'Menambah data Zoom'
        ]);

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
     * Ini adalah sebuah method bernama show yang menerima sebuah parameter id dan mengembalikan sebuah response dalam bentuk JSON. Method ini akan mengambil data dari tabel zooms_vhs dan jadwalvhs di database, menyatukannya, dan mengembalikan data yang memiliki id sesuai dengan parameter id. Jika data tersebut ditemukan, maka akan dikembalikan response dalam bentuk JSON dengan key success dan status kode HTTP yang sesuai dengan $this->successStatus. Jika data tidak ditemukan, maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP yang sesuai dengan $this->errorStatus.
     */
    public function show($id)
    {
        // $data = DB::table('jadwalvhs')->where('id',$id)->first();
        $data = DB::table('zooms_vhs')
            ->join('jadwalvhs', 'jadwalvhs.id', 'zooms_vhs.jadwal_id')
            ->select('zooms_vhs.id as zoom_id', 'jadwalvhs.id as jadwalvhs_id', 'zooms_vhs.name as zoom_name', 'jadwalvhs.name as jadwalvhs_name', 'zooms_vhs.*', 'jadwalvhs.*')
            ->where('zooms_vhs.id', $id)
            ->first();
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
     * Ini adalah sebuah method bernama update yang menerima sebuah request (data yang dikirim dari client) dan sebuah parameter id, dan mengembalikan sebuah response dalam bentuk JSON. Method ini akan mengupdate data pada tabel zooms_vhs di database yang memiliki id sesuai dengan parameter id. Jika proses update berhasil, maka akan dikembalikan response dalam bentuk JSON dengan key success dan message serta status kode HTTP yang sesuai dengan $this->successStatus. Jika terjadi exception (error) selama proses update, maka akan dikembalikan response dalam bentuk JSON dengan key error dan status kode HTTP 500 (Internal Server Error).
     */
    public function update(Request $request, $id)
    {
        $jadwal_id = $request->jadwal_id;
        $name = $request->name;
        $times = $request->times;
        $link = $request->link;
        $meeting_id = $request->meeting_id;
        $password = $request->password;

        try {
            $zoom = ZoomsVhs::findOrfail($id)->update([
                'jadwal_id'         => $jadwal_id,
                'name'              => $name,
                'times'             => $times,
                'link'              => $link,
                'meeting_id'        => $meeting_id,
                'password'          => $password,
            ]);
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Mengupdate data zoom'
            ]);
            return response()->json(
                [
                    'success' => $zoom,
                    'message' => 'update successfully'
                ],

                $this->successStatus
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
     * Ini adalah sebuah method bernama destroy yang menerima sebuah parameter id dan mengembalikan sebuah response dalam bentuk JSON. Method ini akan menghapus data dari tabel zooms_vhs di database yang memiliki id sesuai dengan parameter id. Jika proses penghapusan data berhasil, maka akan dikembalikan response dalam bentuk JSON dengan key message. Jika terjadi exception (error) selama proses penghapusan data, maka akan dilakukan rollback (pembatalan) terhadap seluruh proses yang telah dilakukan sebelumnya dan akan dikembalikan response dalam bentuk HttpException dengan status kode HTTP 500 (Internal Server Error).
     */
    public function destroy($id)
    {
        try {
            ZoomsVhs::destroy($id);
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Menghapus data Zoom'
            ]);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }
}
