<?php

namespace App\Http\Controllers\Api\Pas\Pengaturan\People;

use App\Http\Controllers\Controller;
use App\Models\Pas_ind_penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndPenilaianController extends Controller
{
    /**
     * public function index_per_kpi($id): Mendefinisikan fungsi index_per_kpi yang menerima parameter $id. Parameter ini digunakan untuk mengambil data berdasarkan nilai $id yang diberikan.
     * try { ... }: Membuka blok try-catch, digunakan untuk menangkap dan menangani pengecualian yang mungkin terjadi saat eksekusi kode di dalamnya.
     * $datas = Pas_ind_penilaian::where('kpi_id', $id)->orderBy('nilai', 'desc')->orderBy('grade', 'asc')->get();: Baris ini mengambil data dari model Pas_ind_penilaian menggunakan metode where untuk memfilter berdasarkan kolom kpi_id yang sama dengan nilai $id yang diberikan. Selanjutnya, data diurutkan berdasarkan kolom nilai secara menurun (desc) dan kolom grade secara menaik (asc). Metode get() digunakan untuk mengambil hasil data dalam bentuk koleksi.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi data yang berhasil diambil dan pesan "sukses". Data yang diambil dari database diberikan dengan kunci 'data', sedangkan pesan diberikan dengan kunci 'message'.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     * 
     */
    public function index_per_kpi($id)
    {
        try {
            $datas = Pas_ind_penilaian::where('kpi_id', $id)->orderBy('nilai', 'desc')->orderBy('grade', 'asc')->get();

            return response()->json([
                'data' => $datas,
                'message' => 'sukses'
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    /**
     * public function index(): Fungsi index() ini adalah sebuah method yang digunakan untuk mengambil semua data yang ada dalam tabel Pas_ind_penilaian dan mengembalikan hasilnya dalam format JSON.
     * try { ... }: Blok try-catch digunakan untuk menangkap dan menangani pengecualian yang mungkin terjadi saat eksekusi kode di dalamnya.
     * $datas = Pas_ind_penilaian::all();: Baris ini mengambil semua data yang ada dalam tabel Pas_ind_penilaian menggunakan metode all() dari model Pas_ind_penilaian. Metode ini menghasilkan koleksi dari semua data yang ada dalam tabel tersebut.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi semua data yang berhasil diambil dari tabel Pas_ind_penilaian. Data tersebut diberikan dengan kunci 'data', dan sebuah pesan 'success' juga disertakan dalam respons./
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function index()
    {
        try {
            $datas = Pas_ind_penilaian::all();
            return response()->json(
                [
                    'data' => $datas,
                    'message' => 'success',
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
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
     *public function store(Request $request): Fungsi store() ini digunakan untuk menyimpan data baru ke dalam tabel pas_ind_penilaians berdasarkan permintaan (request) yang diberikan.
     *$validator = Validator::make($request->all(), [...]);: Baris ini mendefinisikan validator untuk melakukan validasi terhadap semua input yang ada dalam permintaan (request). Validasi dilakukan berdasarkan aturan yang ditentukan dalam array.
     *if ($validator->fails()) { ... }: Jika validasi gagal, maka akan mengembalikan respons JSON dengan pesan kesalahan validasi dan status HTTP 401.
     *$cek = DB::table('pas_ind_penilaians')->where([...])->count();: Baris ini menghitung jumlah data dalam tabel pas_ind_penilaians yang memenuhi beberapa kondisi yang ditentukan dengan menggunakan metode where() dari Query Builder. Jumlah data tersebut disimpan dalam variabel $cek.
     *if ($cek) { ... } else { ... }: Jika $cek memiliki nilai yang lebih dari 0 (berarti data yang sama sudah ada dalam tabel), maka akan mengembalikan respons JSON dengan pesan 'Data Sama' dan status HTTP 403. Jika tidak ada data yang sama, maka data baru akan disimpan ke dalam tabel pas_ind_penilaians.
     *$insertedId = DB::table('pas_ind_penilaians')->insertGetId([...]);: Baris ini menyimpan data baru ke dalam tabel pas_ind_penilaians menggunakan metode insertGetId() dari Query Builder. Metode ini akan memasukkan data baru dan mengembalikan ID yang dihasilkan.
     *return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi ID dari data yang baru disimpan (jika sukses) atau pesan kesalahan (jika terjadi pengecualian).
     *} catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_3p' => 'required',
                'kpi_id' => 'required',
                'nilai' => 'required',
                // 'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $cekmax = DB::table('pas_kpis')->where('id',$request->kpi_id)->first();
            if ($request->nilai > $cekmax->max_nilai) {
                return response()->json([
                    'message' => 'Nilai tidak boleh lebih besar dari MAX NILAI kpi'
                ], 403);
            }
            $cek = DB::table('pas_ind_penilaians')
                ->where('3p_id', $request->id_3p)
                ->where('kpi_id', $request->kpi_id)
                ->where('nilai', $request->nilai)
                // ->where('grade', $request->grade)
                ->count();
            if ($cek) {
                return response()->json(
                    [
                        'message' => 'Data Nilai Sama',
                    ],
                    403
                );
            } else {
                $InsertGetId = DB::table('pas_ind_penilaians')->insertGetId([
                    '3p_id' => $request->id_3p,
                    'kpi_id' => $request->kpi_id,
                    'nilai' => $request->nilai,
                    // 'grade' => $request->grade,
                    'desc' => $request->desc,
                ]);

                return response()->json(
                    [
                        'data' => $InsertGetId,
                        'message' => 'success',
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    /**
     * public function show($id): Fungsi show() ini digunakan untuk mengambil data dari tabel pas_ind_penilaians berdasarkan nilai $id yang diberikan.
     * $data = DB::table('pas_ind_penilaians')->where('id', $id)->first();: Baris ini mengambil data dari tabel pas_ind_penilaians menggunakan metode where() untuk memfilter berdasarkan kolom id yang sama dengan nilai $id yang diberikan. Metode first() digunakan untuk mengambil hanya satu baris data pertama yang sesuai dengan kondisi.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi data yang berhasil diambil dari tabel pas_ind_penilaians berdasarkan nilai $id. Data tersebut diberikan dengan kunci 'data', dan sebuah pesan 'success' juga disertakan dalam respons.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function show($id)
    {
        try {
            $data = DB::table('pas_ind_penilaians')
                ->where('id', $id)
                ->first();
            return response()->json(
                [
                    'data' => $data,
                    'message' => 'success',
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
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
     * public function update(Request $request, $id): Fungsi update() ini digunakan untuk memperbarui data dalam tabel pas_ind_penilaians berdasarkan nilai $id yang diberikan.
     * $validator = Validator::make($request->all(), [...]);: Baris ini mendefinisikan validator untuk melakukan validasi terhadap semua input yang ada dalam permintaan (request). Validasi dilakukan berdasarkan aturan yang ditentukan dalam array.
     * if ($validator->fails()) { ... }: Jika validasi gagal, maka akan mengembalikan respons JSON dengan pesan kesalahan validasi dan status HTTP 401.
     * $updatedRows = DB::table('pas_ind_penilaians')->where('id', $request->id)->update([...]);: Baris ini menggunakan metode update() dari Query Builder untuk memperbarui data dalam tabel pas_ind_penilaians berdasarkan kolom 'id' yang sama dengan nilai $id yang diberikan dalam permintaan. Data akan diperbarui dengan nilai-nilai yang diberikan dalam permintaan.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi jumlah baris yang berhasil diperbarui dalam tabel pas_ind_penilaians (dalam variabel $updatedRows) dan pesan 'success'.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'id_3p' => 'required',
                'kpi_id' => 'required',
                'nilai' => 'required',
                // 'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $cekmax = DB::table('pas_kpis')->where('id',$request->kpi_id)->first();
            if ($request->nilai > $cekmax->max_nilai) {
                return response()->json([
                    'message' => 'Nilai tidak boleh lebih besar dari MAX NILAI kpi'
                ], 403);
            }
            // $cek = DB::table('pas_ind_penilaians')
            //     ->where('3p_id', $request->id_3p)
            //     ->where('kpi_id', $request->kpi_id)
            //     ->where('nilai', $request->nilai)
            //     // ->where('grade', $request->grade)
            //     ->count();
            // if ($cek) {
            //     return response()->json(
            //         [
            //             'message' => 'Data Nilai Sama',
            //         ],
            //         403
            //     );
            // }

            $InsertGetId = DB::table('pas_ind_penilaians')->where('id', $request->id)->update([
                '3p_id' => $request->id_3p,
                'kpi_id' => $request->kpi_id,
                'nilai' => $request->nilai,
                // 'grade' => $request->grade,
                'desc' => $request->desc,
            ]);

            return response()->json(
                [
                    'data' => $InsertGetId,
                    'message' => 'success',
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    /**
     * public function destroy($id): Fungsi destroy() ini digunakan untuk menghapus data dari tabel pas_ind_penilaians berdasarkan nilai $id yang diberikan.
     * $delete = Pas_ind_penilaian::findOrFail($id);: Baris ini menggunakan metode findOrFail() dari model Pas_ind_penilaian untuk mencari data berdasarkan kolom 'id' yang sama dengan nilai $id yang diberikan. Jika data tidak ditemukan, maka akan melempar pengecualian (ModelNotFoundException).
     * $delete->delete();: Baris ini menghapus data yang ditemukan dengan menggunakan metode delete() pada objek $delete yang mewakili data yang akan dihapus.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi pesan 'Data Berhasil di Hapus', menandakan bahwa data telah berhasil dihapus.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function destroy($id)
    {
        try {
            $delete = Pas_ind_penilaian::findOrFail($id);
            $delete->delete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }
}
