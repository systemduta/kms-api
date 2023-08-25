<?php

namespace App\Http\Controllers\Api\Pas\Pengaturan\People;

use App\Http\Controllers\Controller;
use App\Models\Pas_kpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KPIController extends Controller
{
    /**
     * public function index_per_dimensi($id): Fungsi index_per_dimensi() ini digunakan untuk mengambil data dari tabel pas_kpis yang terkait dengan dimensi berdasarkan nilai $id yang diberikan.
     * $userData = auth()->user();: Baris ini mengambil data pengguna yang saat ini terotentikasi.
     * ->when($userData->role != 1, function ($q) use ($userData) { ... }): Pada baris ini, dilakukan pengecekan apakah peran (role) pengguna bukan 1 (admin). Jika bukan admin, maka akan diterapkan kondisi tambahan dalam query untuk memfilter data berdasarkan company_id dari pengguna terotentikasi.
     * ->where('pas_kpis.dimensi_id', $id): Baris ini menambahkan kondisi where untuk memfilter data berdasarkan dimensi_id yang diberikan.
     * ->select('pas_kpis.id', ... , 'pas_kpis.updated_at'): Baris ini menentukan kolom-kolom yang akan diambil dalam query. Setiap kolom akan diberi alias menggunakan as untuk mempermudah pemanggilan nantinya.
     * ->get(): Baris ini mengeksekusi query dan mengambil hasil data dalam bentuk koleksi (collection)
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi data yang diperoleh dari query dan pesan 'success'.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function index_per_dimensi($id)
    {
        try {
            $userData = auth()->user();            
            $cek = DB::table('permissions')->where('user_id', $userData->id)->where('isSuperAdmin', 1)->first();
            $datas = DB::table('pas_kpis')
                ->join('pas_3p', 'pas_kpis.3p_id', '=', 'pas_3p.id')
                ->join('pas_dimensis', 'pas_kpis.dimensi_id', '=', 'pas_dimensis.id')
                ->leftjoin('companies', 'pas_kpis.company_id', '=', 'companies.id')
                ->leftjoin('organizations', 'pas_kpis.division_id', '=', 'organizations.id')
                // ->when($userData->role!=1, function ($q) use ($userData) {
                //     return $q->where('pas_kpis.company_id', $userData->company_id);
                // })
                ->where('pas_kpis.dimensi_id', $id)
                ->select('pas_kpis.id', 'pas_3p.name as name_3p', 'pas_dimensis.name as name_dimensi', 'companies.name as name_company','organizations.name as name_organization','pas_kpis.name','pas_kpis.max_nilai', 'pas_kpis.created_at', 'pas_kpis.updated_at');

            if (!$cek) {
                $datas->where('pas_kpis.company_id', $userData->company_id);
            }
            $datas = $datas->get();
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
     * public function index(): Fungsi index() ini digunakan untuk mengambil semua data dari tabel Pas_kpi.
     * $datas = Pas_kpi::all();: Baris ini menggunakan model Pas_kpi dan metode all() untuk mengambil semua data dari tabel Pas_kpi dan menyimpannya dalam variabel $datas.
     * return response()->json([...]);: Baris ini mengembalikan respons JSON yang berisi data yang diperoleh dari tabel Pas_kpi dalam variabel $datas dan pesan 'success'./
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function index()
    {
        try {
            $datas = Pas_kpi::all();
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
     * public function store(Request $request): Fungsi store() digunakan untuk menyimpan data baru ke dalam tabel pas_kpis. Fungsi ini menerima objek $request yang berisi data yang dikirimkan melalui permintaan HTTP.
     * $validator = Validator::make($request->all(), [...]);: Baris ini menggunakan fasilitas validasi Laravel untuk memvalidasi data yang diterima. Validator akan memeriksa apakah semua field yang diperlukan (id_3p, dimensi_id, name, max_nilai) ada dalam permintaan dan memastikan bahwa mereka sesuai dengan aturan validasi yang didefinisikan.
     * if ($validator->fails()) { ... }: Jika validasi gagal (ada kesalahan validasi), maka blok ini akan dieksekusi. Fungsi ini akan mengembalikan respons JSON dengan daftar kesalahan validasi dan status HTTP 401.
     * $InsertGetId = DB::table('pas_kpis')->insertGetId([...]);: Jika validasi berhasil, baris ini akan menambahkan data baru ke dalam tabel pas_kpis menggunakan metode insertGetId(). Metode ini akan memasukkan data baru dan mengembalikan ID data yang baru ditambahkan.
     * return response()->json([...]);: Setelah data berhasil disimpan, fungsi akan mengembalikan respons JSON yang berisi ID data yang baru ditambahkan dan pesan 'success'.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_3p' => 'required',
                'dimensi_id' => 'required',
                'name' => 'required',
                'max_nilai' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_kpis')->insertGetId([
                '3p_id' => $request->id_3p,
                'dimensi_id' => $request->dimensi_id,
                'name' => $request->name,
                'max_nilai' => $request->max_nilai,
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
     * public function show($id): Fungsi show() digunakan untuk mengambil data dari tabel pas_kpis berdasarkan ID yang diberikan. Fungsi ini menerima parameter $id yang merupakan ID dari data yang akan diambil.
     * $data = DB::table('pas_kpis')->where('id', $id)->first();: Baris ini menggunakan metode DB::table() untuk membuat query ke tabel pas_kpis. Kemudian, kita menggunakan metode where() untuk memfilter data berdasarkan kolom 'id' yang sesuai dengan nilai $id. Terakhir, metode first() digunakan untuk mengambil satu baris data pertama yang sesuai dengan kriteria tersebut.
     * return response()->json([...]);: Setelah data berhasil diambil, fungsi akan mengembalikan respons JSON yang berisi data yang ditemukan dan pesan 'success'.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function show($id)
    {
        try {
            $data = DB::table('pas_kpis')
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
     * public function update(Request $request, $id): Fungsi update() digunakan untuk mengubah data pada tabel pas_kpis berdasarkan ID yang diberikan. Fungsi ini menerima parameter $request yang berisi data permintaan dari klien dan $id yang merupakan ID dari data yang akan diubah.
     * $validator = Validator::make($request->all(), [...]);: Baris ini menggunakan Validator untuk memvalidasi data yang diterima dari permintaan. Validator didefinisikan dengan aturan validasi yang harus dipenuhi oleh data.
     * if ($validator->fails()) { ... }: Jika validasi gagal (terdapat kesalahan validasi), maka fungsi akan mengembalikan respons JSON dengan daftar kesalahan validasi yang terjadi.
     * $InsertGetId = DB::table('pas_kpis')->where('id', $request->id)->update([...]);: Baris ini menggunakan metode update() pada tabel pas_kpis untuk mengubah data yang sesuai dengan ID yang diberikan. Data yang akan diubah diambil dari $request sesuai dengan kolom yang ditentukan.
     * return response()->json([...]);: Setelah data berhasil diubah, fungsi akan mengembalikan respons JSON yang berisi informasi bahwa perubahan data sukses dilakukan.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'id_3p' => 'required',
                'dimensi_id' => 'required',
                'name' => 'required',
                'max_nilai' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_kpis')->where('id', $request->id)->update([
                '3p_id' => $request->id_3p,
                'dimensi_id' => $request->dimensi_id,
                'name' => $request->name,
                'max_nilai' => $request->max_nilai,
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
     * public function destroy($id): Fungsi destroy() digunakan untuk menghapus data pada tabel pas_kpis berdasarkan ID yang diberikan. Fungsi ini menerima parameter $id yang merupakan ID dari data yang akan dihapus.
     * $delete = Pas_kpi::findOrFail($id);: Baris ini mencoba untuk menemukan data Pas_kpi berdasarkan ID yang diberikan. Jika data dengan ID tersebut tidak ditemukan, maka metode findOrFail() akan membangkitkan pengecualian ModelNotFoundException.
     * $delete->delete();: Jika data ditemukan, maka metode delete() akan dipanggil pada objek data tersebut untuk menghapusnya dari database.
     * return response()->json([...]);: Setelah data berhasil dihapus, fungsi akan mengembalikan respons JSON dengan pesan bahwa data berhasil dihapus.
     * } catch (\Exception $e) { ... }: Blok catch digunakan untuk menangkap pengecualian jika terjadi kesalahan saat eksekusi kode di dalam blok try. Jika pengecualian terjadi, maka pesan kesalahan akan dikembalikan dalam respons JSON dengan status HTTP 403.
     */
    public function destroy($id)
    {
        try {
            $delete = Pas_kpi::findOrFail($id);
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
