<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwalvhs;
use App\Models\QuotaAP;
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
    public function copyJadwal($id)
    {
        try {
            $typeVHs = DB::table('jadwalvhs')
                ->where('id', $id)
                ->select('type')
                ->first();

            if ($typeVHs->type == '1VHS Basic') {
                $dataJadwal = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwalvhs.id', '=', 'jadwal_user_vhs.jadwal_id')
                    ->where('jadwalvhs.id', $id)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    ->select('jadwalvhs.*')
                    ->first();

                $name = "1VHS Class - copy";
                $batch = $dataJadwal->batch;
                $type = $dataJadwal->type;
                $start = $dataJadwal->start;
                $end = $dataJadwal->end;
                $isCity = $dataJadwal->isCity;
                $quota = $dataJadwal->quota;

                //insert jadwalvhs
                $id_jadwalvhs_copy = DB::table('jadwalvhs')->insertGetId([
                    'name' => $name,
                    'batch' => $batch,
                    'type' => $type,
                    'start' => $start,
                    'end' => $end,
                    'isCity' => $isCity,
                    'quota' => $quota,
                ]);

                //insert 
                $jadwalUserVhs = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwalvhs.id', '=', 'jadwal_user_vhs.jadwal_id')
                    ->where('jadwalvhs.id', $id)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    ->select('jadwal_user_vhs.*')
                    ->get();
                foreach ($jadwalUserVhs as $user) {
                    $cek = DB::table('users')
                        ->where('id', $user->user_id)
                        ->where('isBasic', 1)
                        ->first();
                    //URUNG kurang sitik copy data
                    $id_jadwaluservhs_copy = DB::table('jadwal_user_vhs')->insertGetId([
                        'user_id' => $cek->id,
                        'jadwal_id' => $id_jadwalvhs_copy,
                        'company_id' => $cek->id,
                    ]);
                }


                // return response()->json([
                //     'data'      => $datas,
                //     'message'   => 'Data Berhasil diupdate!'
                // ], $this->successStatus);
            }
            if ($typeVHs->type == '1VHS Class') {
                return response()->json([
                    'message'   => 'Masuk Cass'
                ], $this->errorStatus);
            }
            if ($typeVHs->type == '1VHS Camp') {
                return response()->json([
                    'message'   => 'Masuk Basic'
                ], $this->errorStatus);
            }
            if ($typeVHs->type == '1VHS Academy') {
                return response()->json([
                    'message'   => 'Masuk Basic'
                ], $this->errorStatus);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function index()
    {
    }

    /**
     * Ini adalah method yang digunakan untuk mengambil semua data jadwalvhs dari database dan mengembalikan data tersebut dalam bentuk JSON ke client. Method ini menggunakan try-catch untuk menangani kemungkinan kesalahan yang terjadi selama proses.
     * Method ini menggunakan class Jadwalvhs untuk mengakses method orderBy() dan get(). Method orderBy() digunakan untuk mengurutkan data jadwalvhs berdasarkan kolom id dengan urutan DESC (descending). Method get() digunakan untuk mengambil semua data yang diurutkan dari database.
     * Jika proses berhasil, maka method ini akan mengembalikan respon dengan data jadwalvhs dalam format JSON ke client menggunakan helper response() dengan method json(). Jika terjadi kesalahan, maka method ini akan mengembalikan respon dengan pesan kesalahan dan status kode 500 (internal server error) ke client.
     */
    public function sop_all()
    {
        try {
            return response()->json(['data' => Jadwalvhs::orderBy("id", 'DESC')->get()]);
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
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'batch'             => 'required',
            'start'             => 'required',
            'end'               => 'required',
            'type'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $tokenUser = DB::table('users')
            ->join('organizations', 'organizations.id', 'users.organization_id')
            ->where('organizations.is_str', '=', '1')
            ->where('token', '!=', "")
            ->pluck('token')
            ->toArray();

        $type = $request->isCity;
        $quotaAps = json_decode($request->quotaAP);
        if ($type == 1 || $type == 2) {
            try {
                DB::beginTransaction();
                $JadwalGetId = DB::table('jadwalvhs')->insertGetId([
                    'name'       => $request->name,
                    'batch'      => $request->batch,
                    'type'       => $request->type,
                    'start'      => $request->start,
                    'end'        => $request->end,
                    'isCity'     => $request->isCity,
                    'quota'      => $request->quota,
                ]);
                DB::commit();

                foreach ($quotaAps as $quotaAp) {
                    DB::table('quotaaps')->insert([
                        'jadwal_id'        => $JadwalGetId,
                        'company_id'       => $quotaAp->id,
                        'quota'       => $quotaAp->quota,
                    ]);
                }
                if ($tokenUser) {
                    $result = fcm()->to($tokenUser)
                        ->timeToLive(0)
                        ->priority('high')
                        ->notification([
                            'title' => 'Hai, ada jadwal 1VHS baru nih segera daftarkan teman perusahaan mu!',
                            'body' => 'Silahkan buka website admin',
                        ])
                        ->data([
                            'title' => 'Hai, ada jadwal 1VHS baru nih segera daftarkan teman perusahaan mu!',
                            'body' => 'Silahkan buka website admin',
                        ])
                        ->send();
                }
                return response()->json([
                    'data'      => $JadwalGetId,
                    'status'    => '1/2',
                    'message'   => 'Data Berhasil disimpan!'
                ], $this->successStatus);
            } catch (\Exception $exception) {
                DB::rollBack();
                throw new HttpException(500, $exception->getMessage(), $exception);
            }
        } else {
            DB::beginTransaction();
            $JadwalGetId = DB::table('jadwalvhs')->insertGetId([
                'name'       => $request->name,
                'batch'      => $request->batch,
                'type'       => $request->type,
                'start'      => $request->start,
                'end'        => $request->end,
                'isCity'     => $request->isCity ?? '4',
                'quota'      => $request->quota,
            ]);
            DB::commit();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
                    ->notification([
                        'title' => 'Hai, ada jadwal 1VHS baru nih segera daftarkan teman perusahaan mu!',
                        'body' => 'Silahkan buka website admin',
                    ])
                    ->data([
                        'title' => 'Hai, ada jadwal 1VHS baru nih segera daftarkan teman perusahaan mu!',
                        'body' => 'Silahkan buka website admin',
                    ])
                    ->send();
            }
            return response()->json([
                'data'      => $JadwalGetId,
                'message'   => 'Data Berhasil disimpan!'
            ], $this->successStatus);
        }
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
        $data = DB::table('jadwalvhs')->where('id', $id)->first();
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
        $type = $request->type;
        $start = $request->start;
        $end = $request->end;
        $isCity = $request->isCity;
        $quota = $request->quota;

        $tokenUser = DB::table('users')
            ->join('organizations', 'organizations.id', 'users.organization_id')
            ->where('organizations.is_str', '=', '1')
            ->where('token', '!=', "")
            ->pluck('token')
            ->toArray();

        $quotaAps = json_decode($request->quotaAP);
        $reIsCity = Jadwalvhs::where('id', $id)->first();
        $reIsCity = $reIsCity->isCity; //mengambil data isCity sebelumnya (is City == apakah dalam kota atau luar kota baik dengan kuota atau tanpa kuota)

        try {
            if ($reIsCity == 1) {
                if ($isCity == 4 || $isCity == 3) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        try {
                            $jadwal               = Jadwalvhs::find($id);
                            $jadwal->name         = $name;
                            $jadwal->batch        = $batch;
                            $jadwal->type         = $type;
                            $jadwal->start        = $start;
                            $jadwal->end          = $end;
                            $jadwal->isCity       = $isCity;
                            $jadwal->quota        = $quota;
                            $jadwal->save();

                            $tblQuota = DB::table('quotaaps')->where('jadwal_id', $id)->get();
                            foreach ($tblQuota as $item) {
                                $data = QuotaAP::where('jadwal_id', $item->jadwal_id)->first();
                                if ($data) {
                                    $data->delete();
                                }
                            }
                            return response()->json([
                                'data'      => $jadwal,
                                'message'   => 'Data Berhasil diupdate!'
                            ], $this->successStatus);
                        } catch (\Exception $exception) {
                            DB::rollBack();
                            throw new HttpException(500, $exception->getMessage(), $exception);
                        }
                    }
                }
                if ($isCity == 2) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal               = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json(
                            [
                                'success' => $jadwal,
                                'message' => 'update successfully'
                            ],
                            $this->successStatus
                        );
                    }
                }
            }
            if ($reIsCity == 2) {
                if ($isCity == 4 || $isCity == 3) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal               = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        $tblQuota = DB::table('quotaaps')->where('jadwal_id', $id)->get();
                        foreach ($tblQuota as $item) {
                            $data = QuotaAP::where('jadwal_id', $item->jadwal_id)->first();
                            if ($data) {
                                $data->delete();
                            }
                        }
                        return response()->json([
                            'data'      => $jadwal,
                            'message'   => 'Data Berhasil diupdate!'
                        ], $this->successStatus);
                    }
                }
                if ($isCity == 1) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal               = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json(
                            [
                                'success' => $jadwal,
                                'message' => 'update successfully'
                            ],
                            $this->successStatus
                        );
                    }
                }
            }
            if ($reIsCity == 3) {
                if ($isCity == 4) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json([
                            'data'      => $jadwal,
                            'message'   => 'Data Berhasil diupdate!'
                        ], $this->successStatus);
                    }
                }
                if ($isCity == 2 || $isCity == 1) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal               = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json(
                            [
                                'success' => $jadwal,
                                'message' => 'update successfully'
                            ],
                            $this->successStatus
                        );
                    }
                }
            }
            if ($reIsCity == 4) {
                if ($isCity == 3) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json([
                            'data'      => $jadwal,
                            'message'   => 'Data Berhasil diupdate!'
                        ], $this->successStatus);
                    }
                }
                if ($isCity == 2 || $isCity == 1) {
                    $userAp = DB::table('jadwal_user_vhs')->where('jadwal_id', $id)->get();
                    if (count($userAp) > 0) {
                        return response()->json("Ada user yang terdaftar silahkan hapus dahulu", $this->errorStatus);
                    } else {
                        $jadwal               = Jadwalvhs::find($id);
                        $jadwal->name         = $name;
                        $jadwal->batch        = $batch;
                        $jadwal->type         = $type;
                        $jadwal->start        = $start;
                        $jadwal->end          = $end;
                        $jadwal->isCity       = $isCity;
                        $jadwal->quota        = $quota;
                        $jadwal->save();

                        return response()->json(
                            [
                                'success' => $jadwal,
                                'message' => 'update successfully'
                            ],
                            $this->successStatus
                        );
                    }
                }
            }
            if ($reIsCity == $isCity) {
                $jadwal               = Jadwalvhs::find($id);
                $jadwal->name         = $name;
                $jadwal->batch        = $batch;
                $jadwal->type         = $type;
                $jadwal->start        = $start;
                $jadwal->end          = $end;
                $jadwal->isCity       = $isCity;
                $jadwal->quota        = $quota;
                $jadwal->save();

                return response()->json(
                    [
                        'success' => $jadwal,
                        'message' => 'update successfully'
                    ],
                    $this->successStatus
                );
            }
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
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
        $idDestroy = Jadwalvhs::find($id);
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
