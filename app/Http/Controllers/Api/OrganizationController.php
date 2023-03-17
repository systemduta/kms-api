<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
     /**
      * Ini adalah sebuah method bernama deleteOrg yang menerima parameter $id. Method ini digunakan untuk menghapus data dari tabel organizations dengan id tertentu.
      * Pertama, method ini akan mengecek apakah ada data di tabel users yang memiliki organization_id yang sama dengan $id. Jika ada, maka method ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi pesan error.
      * Jika tidak ada data di tabel users yang memiliki organization_id yang sama dengan $id, maka method ini akan menghapus data dari tabel organizations dengan menggunakan fungsi destroy. Kemudian, method ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
      * Pada baris terakhir, terdapat sebuah catch statement yang digunakan untuk menangkap setiap exception yang terjadi. Jika terjadi exception, maka method ini akan mengembalikan sebuah response dalam bentuk JSON yang berisi pesan error.
      */
    public function deleteOrg($id){
        try {
            $data = DB::table('organizations')
                    ->join('users','users.organization_id','=','organizations.id')
                    ->where('organizations.id',$id)
                    ->count();
            if ($data > 0) {
                return response()->json(['error' => 'Maaf ada user yang terdaftar. Silahkan hapus atau pindah user terlebih dahulu']);
            } 
            else{
                try {                    
                    Organization::destroy($id);
                    return response()->json(['sukses' => 'berhasil menghapus data']);
                } catch (\Throwable $th) {
                    return response()->json(['error' => $th->getMessage()]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    /**
     * hubungan route: 
     *      Route::get('get_organization', 'OrganizationController@index');
     * 
     * funsgi :
     * untuk memperoleh data divisi/organozation saat ini
     */
    public function index()
    {
        $user = auth()->user();
        // $organization = Organization::query()
        //     ->when(($user && $user->role!=1), function ($q) use ($user) {
        //         return $q->where('company_id', $user->company_id);
        //     })
        //     ->get();
        $organization = DB::table('organizations')
            ->join('companies','organizations.company_id','=','companies.id')
            // ->when(($user && $user->role!=1), function ($q) use ($user) {
            //     return $q->where('company_id', $user->company_id);
            //     })
            ->select('organizations.*','companies.name as name_company')
            ->get();
        return response()->json(['data' => $organization]);
    }
    public function organization_company()
    {
        $user = auth()->user();
        $organization = DB::table('organizations')
            ->join('companies','organizations.company_id','=','companies.id')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('company_id', $user->company_id);
                })
            ->select('organizations.*','companies.name as name_company')
            ->get();
        return response()->json(['data' => $organization]);
    }

//    public function get_organization_by_company(Request $request)
//    {
//        $company_id = auth()->user->company_id;
//        $data = DB::table('organizations')
//            ->where('company_id', $company_id)
//            ->selectRaw('id,code,name,is_str')->get();
//        return response()->json(['data' => $data]);
//    }

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
     * Ini adalah sebuah fungsi bernama store yang menerima sebuah parameter bernama Request yang berisi data yang dikirimkan oleh client. Fungsi ini kemudian melakukan validasi terhadap data yang dikirimkan menggunakan class Validator. Jika validasi gagal, fungsi akan mengembalikan pesan error ke client. Jika validasi berhasil, fungsi akan mengecek apakah user yang sedang login memiliki company_id, jika tidak memiliki maka akan menggunakan company_id yang dimiliki oleh user tersebut. Kemudian, fungsi akan membuat objek baru dari class Organization dan mengisi data yang dikirimkan oleh client ke dalam objek tersebut. Setelah itu, objek tersebut akan disimpan ke dalam database. Jika proses penyimpanan berhasil, fungsi akan mengembalikan pesan sukses ke client.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
            'company_id' => 'numeric|nullable',
            'isAdm' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        
        $user = auth()->user();

        $organization = new Organization();
        $organization->company_id = $request->company_id ?? $user->company_id;
        $organization->parent_id = null;
        $organization->name = $request->name;
        $organization->code = $request->code;
        $organization->isAdm = $request->isAdm;
        $organization->iterasi = 0;
        $organization->is_str = $request->is_str ?? 0;
        $organization->save();

        return response()->json([
            'data' => $organization,
            'message' => 'Data berhasil disimpan!'
        ], 200);
    }

    /**
     * Ini adalah sebuah fungsi bernama show yang menerima sebuah parameter bernama Organization yang merupakan objek dari class Organization. Fungsi ini kemudian mengembalikan objek tersebut ke client dalam bentuk JSON. Jadi, fungsi ini akan menampilkan detail dari objek Organization yang diberikan sebagai parameter.
     */
    public function show(Organization $organization)
    {
        return response()->json(['data' => $organization]);
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
     * Ini adalah sebuah fungsi yang disebut update yang menerima dua parameter, yaitu Request dan Organization. Fungsi ini digunakan untuk mengupdate data dari suatu objek Organization. Pertama-tama, fungsi ini memvalidasi input yang diberikan dengan menggunakan validator dari Laravel. Kemudian, fungsi ini mengambil objek user yang sedang login saat ini dengan memanggil auth()->user().
     * Setelah itu, objek Organization yang diberikan sebagai parameter akan diupdate dengan data baru yang diberikan dari request. Nilai dari beberapa field akan diubah sesuai dengan input yang diberikan, sedangkan untuk beberapa field lainnya akan diisi dengan nilai default. Setelah semua data terupdate, objek Organization tersebut akan disimpan kembali ke database. Terakhir, fungsi ini akan mengembalikan respons dengan data objek Organization yang telah terupdate dan pesan yang menyatakan bahwa data telah berhasil diupdate.
     */
    public function update(Request $request, Organization $organization)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'code' => 'string|required',
            'company_id' => 'numeric|nullable',
            'isAdm' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = auth()->user();

        $organization->company_id = $request->company_id ?? $user->company_id;
        $organization->parent_id = null;
        $organization->name = $request->name;
        $organization->code = $request->code;
        $organization->iterasi = 0;
        $organization->isAdm = $request->isAdm;
        $organization->save();

        return response()->json([
            'data' => $organization,
            'message' => 'Data berhasil diupdate!'
        ], 200);
    }

    /**
     * Ini adalah sebuah method bernama destroy yang menerima parameter $id. Method ini digunakan untuk menghapus data dari tabel Organization dengan id tertentu.
     * Pada baris ketiga, terdapat sebuah query yang akan mengambil data pada tabel organization sesuai dengan object $organization yang dikirim sebagai parameter. Kemudian, fungsi delete akan menghapus data tersebut dari tabel.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json(['message' => 'Delete Successfully']);
    }
}
