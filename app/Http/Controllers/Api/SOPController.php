<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Crossfunction;
use App\Models\Lampiran;
use App\Models\Sop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SOPController extends Controller
{
    public $successStatus = 200; //sebuah variable yang akan dipanggil saat suatu proses sukses dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah contoh fungsi getall yang tidak menerima parameter di PHP. Fungsi ini terlihat seperti mengambil dan mengembalikan semua data dari tabel atau model Company di database.
     * Fungsi ini pertama kali mengambil objek user yang sedang login dengan menggunakan auth()->user(). Kemudian, fungsi tersebut mengembalikan respons JSON dengan data dari tabel atau model Company yang diurutkan berdasarkan kolom id secara descending (dari yang terbaru). Jika objek user yang sedang login merupakan user dengan role yang bukan 1 (bukan admin), maka query akan dibatasi hanya menampilkan data dari perusahaan yang memiliki id sama dengan $user->company_id. Jika tidak, maka query akan menampilkan semua data.
     */
    public function getall()
    {
        $user = auth()->user();
        return response()->json(
            ['data' => Company::when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('id', $user->company_id);
                })->orderBy('id','DESC')->get()]
        );
    }

    /**
     * Ini adalah contoh fungsi getOrg yang menerima parameter $id di PHP. Fungsi ini terlihat seperti mengambil dan mengembalikan data perusahaan dan divisi yang terkait dengan $id dari tabel companies dan organizations di database.
     * Fungsi ini mengambil data perusahaan dengan mencari satu baris dari tabel companies yang memiliki id yang sama dengan $id, kemudian mengurutkannya berdasarkan kolom id secara ascending (dari yang terkecil). Kemudian, fungsi tersebut mengambil data divisi dengan melakukan join tabel companies dan organizations dengan kondisi companies.id sama dengan organizations.company_id, kemudian menyaringnya dengan kondisi companies.id sama dengan $id. Kemudian, fungsi tersebut mengembalikan respons JSON dengan data perusahaan dan data divisi yang dihasilkan dari query sebelumnya.
     */
    public function getOrg($id)
    {
        $detailCompany =  Company::where('id',$id)->orderBy('id','ASC')->get();
        $listDivision = DB::table('companies')
            ->join('organizations','organizations.company_id','=','companies.id')
            ->where('companies.id',$id)
            ->get();
        return response()->json(
            [
                'detailcompany' =>$detailCompany,
                'listorganizations' =>$listDivision,
            ]
        );
    }

    /**
     * Ini adalah sebuah method bernama index yang menerima parameter Request $request. Method ini digunakan untuk menampilkan data dari tabel Sop.
     * Pada baris pertama, variabel $auth diinisialisasikan dengan objek user yang sedang login saat ini. Kemudian, variabel $data diinisialisasikan dengan data dari tabel Sop yang memiliki relasi dengan tabel company dan organization menggunakan fungsi with.
     * Pada baris ketiga, terdapat sebuah when statement yang memiliki sebuah closure sebagai parameter. Jika role dari user yang sedang login tidak sama dengan 1, maka closure tersebut akan dijalankan dan menambahkan kondisi where pada query untuk mencari data yang memiliki company_id sama dengan company_id dari user yang sedang login.
     * Pada baris keempat, terdapat kondisi where yang mencari data yang memiliki organization_id sama dengan organization_id yang dikirim dalam request. Kemudian, data tersebut diurutkan berdasarkan kolom id dengan urutan DESC (descending) dan di-return sebagai response dalam bentuk JSON.
     */
    public function index(Request $request)
    {
        $auth = auth()->user();
        $data = Sop::with(['company','organization'])
                ->when($auth->role!=1, function ($q) use ($auth) {
                    return $q->where('company_id', $auth->company_id);
                })
                ->where('organization_id', $request->organization_id)
                ->orderBy('id', 'DESC')
                ->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Ini adalah sebuah method bernama sop yang tidak menerima parameter apapun. Method ini digunakan untuk menampilkan seluruh data dari tabel Sop.
     * Pada baris ketiga, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data dari tabel Sop yang diambil menggunakan fungsi get. Fungsi get akan mengambil semua data dari tabel Sop dan mengembalikannya dalam bentuk array.
     */
    public function sop()
    {
        return response()->json(['data' => Sop::get()]);
    }

    /**
     * Ini adalah sebuah method bernama sopdown yang menerima parameter $id dan digunakan untuk mengirim tempat dimana file sop berada sehingga dapat digunakan untuk download data dibagian frontend nanti. Method ini digunakan untuk mengambil file yang terkait dengan data Sop dengan id tertentu dari tabel sops.
     * Pada baris pertama, terdapat sebuah variabel $sop yang diinisialisasikan dengan hasil dari query yang mengambil kolom file dari tabel sops dengan kondisi id yang sesuai dengan parameter $id menggunakan fungsi select dan where. Fungsi first akan mengambil satu baris data pertama yang dikembalikan oleh query tersebut.
     * Pada baris ketiga, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi file yang terkait dengan data Sop yang bersangkutan. File tersebut diambil dari kolom file yang tersimpan dalam objek $sop.
     */
    public function sopdown($id)
    {
        $sop = DB::table('sops')->select('file')->where('id',$id)->first();
        return response()->json(['data' => $sop->file]);
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
     * Ini adalah sebuah method bernama store yang menerima parameter Request $request. Method ini digunakan untuk menyimpan data baru ke dalam tabel sops.
     * Pada baris ketiga, terdapat sebuah validasi untuk memastikan bahwa semua field yang dibutuhkan telah diisi dengan benar. Jika terdapat field yang belum diisi atau tidak sesuai dengan validasi yang ditentukan, maka akan dikembalikan sebuah error dengan status HTTP 401 (Unauthorized).
     * Pada baris kelima, terdapat sebuah variabel $auth yang diinisialisasikan dengan objek user yang sedang login saat ini.
     * Baris keenam hingga kelima belas merupakan proses upload file yang dikirim dalam request. File tersebut dikonversi menjadi base64 string, kemudian di-decode dan disimpan ke dalam folder public/files dengan nama yang telah ditentukan.
     * Baris tujuh belas hingga kelima puluh merupakan proses penyimpanan data ke dalam tabel sops. Pertama, data akan disimpan ke dalam tabel menggunakan fungsi insertGetId yang akan mengembalikan id dari data yang baru saja disimpan. Kemudian, jika terdapat data gambar yang dikirim dalam request, maka data tersebut akan di-update dengan menggunakan fungsi update.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi id dari data yang baru saja disimpan dan pesan sukses.
     */
    public function store(Request $request)
    {
        error_reporting(0);
        $validator = Validator::make($request->all(), [
            'organization_id'   => 'required',
            'company_id'        => 'required',
            'image'             => 'required',
            'title'             => 'required',
            'description'       => 'required',
            'file'              => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],401);
        }

        $auth = auth()->user();

        //* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'sop_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        try {
            $organization_id = $request->organization_id ?? null;
            $tokenUser = DB::table('users')
                // ->when($auth->role!=1, function ($q) use ($auth) {
                //     return $q->where('company_id', $auth->company_id);
                // })
                ->when( $organization_id, function ($query) use ($organization_id) {
                    return $query->where('organization_id', $organization_id);
                })
                ->where('token','!=',"")
                ->pluck('token')->toArray();
            if($tokenUser) {
                $result = fcm()->to($tokenUser)
                ->timeToLive(0)
                ->priority('high')
                ->notification([
                    'title' => 'Hai, ada SOP baru nih buat kamu!',
                    'body' => $request->title,
                ])
                ->data([
                    'title' => 'Hai, ada SOP baru nih buat kamu!',
                    'body' => $request->title,
                ])
                ->send();
            }
            DB::beginTransaction();
            $sopGetId = DB::table('sops')->insertGetId([
                // 'company_id'        => $auth->company_id,
                'company_id'        => $request->company_id,
                'organization_id'   => $request->organization_id ?? null,
                'title'             => $request->title,
                'image'             => '',
                'description'       => $request->description,
                'file'              => 'files/'.$filename,
                // 'file'              => env('APP_URL') . '/' . $url,
            ]);

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
                DB::table('sops')->where('id', $sopGetId)->update(['image' => $imgName]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $sopGetId,
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
     * Ini adalah sebuah method bernama show yang menerima parameter $id. Method ini digunakan untuk menampilkan data dari tabel sops dengan id tertentu.
     * Pada baris pertama, terdapat sebuah variabel $data yang diinisialisasikan dengan hasil dari query yang mengambil data dari tabel sops dengan kondisi id yang sesuai dengan parameter $id menggunakan fungsi where. Fungsi first akan mengambil satu baris data pertama yang dikembalikan oleh query tersebut.
     * Pada baris ketiga, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data yang bersangkutan.
     */
    public function show($id)
    {
        $data = DB::table('sops')->where('id', $id)->first();
        return response()->json(['success' => $data], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama status yang menerima parameter $id. Method ini digunakan untuk mengupdate status data dari tabel sops dengan id tertentu.
     * Pada baris pertama, terdapat sebuah variabel $data yang diinisialisasikan dengan hasil dari query yang mengambil data dari tabel sops dengan kondisi id yang sesuai dengan parameter $id menggunakan fungsi where.
     * Pada baris ketiga, terdapat sebuah variabel $st_sekarang yang diinisialisasikan dengan nilai status dari data yang bersangkutan.
     * Pada baris kelima hingga ketujuh, terdapat sebuah if statement yang memeriksa apakah nilai status saat ini adalah 1. Jika benar, maka status akan diupdate menjadi 2. Sebaliknya, jika status saat ini bukan 1, maka status akan diupdate menjadi 1. Update tersebut dilakukan dengan menggunakan fungsi save.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function status($id)
    {
        $data = Sop::where('id',$id)->first();
        // dd($data->title);

        $st_sekarang = $data->status;

        if ($st_sekarang == 1) {
            $sop = Sop::find($id);
            $sop->status = 2;
            $sop->save();
        }else{
            $sop = Sop::find($id);
            $sop->status = 1;
            $sop->save();
        }

        return response()->json(['message' => 'Data Update Successfully'],$this->successStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Ini adalah sebuah method bernama update yang menerima parameter Request $request dan $id. Method ini digunakan untuk mengupdate data dari tabel sops dengan id tertentu.
     * Pada baris ketiga hingga kesebelas, terdapat sejumlah variabel yang diinisialisasikan dengan nilai yang dikirim dalam request.
     * Baris ketiga belas hingga kelima belas merupakan proses upload file yang dikirim dalam request. File tersebut dikonversi menjadi base64 string, kemudian di-decode dan disimpan ke dalam folder public/files dengan nama yang telah ditentukan. Kemudian, data file akan diupdate ke dalam tabel sops menggunakan fungsi update.
     * Baris kelima belas hingga kelima tujuh merupakan proses upload gambar yang dikirim dalam request. Gambar tersebut dikonversi menjadi sebuah objek gambar, kemudian disimpan ke dalam folder public/files dengan nama yang telah ditentukan. Kemudian, data gambar akan diupdate ke dalam tabel sops menggunakan fungsi update.
     * Baris kelima tujuh hingga kelima sembilan merupakan proses update data lainnya ke dalam tabel sops menggunakan fungsi update.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi data yang telah diupdate dan pesan sukses.
     */
    public function update(Request $request, $id)
    {
        $title = $request->title;
        $description = $request->description;
        $companyid = $request->company_id;
        $organizationid = $request->organization_id;
        $image = '';

        if ($request->hasFile('file')) {
            /* START FILE UPLOAD */
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',')+1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = 'sop_'.Str::random(10).'.'.$ext;
            Storage::disk('public')->put('files/'.$filename, base64_decode($file));
            /* END FILE UPLOAD */

            $updatefile= Sop::findOrFail($id)->update([
                'file'              => 'files/'.$filename,
            ]);
        }        

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
            $updateimage= Sop::findOrFail($id)->update([
                'image'        => $imgName,
            ]);
        }

        // $course = Sop::find($id);
        // $course->title = $title;
        // $course->description = $description;
        // $course->company_id = $companyid;
        // $course->organization_id = $organizationid;
        // $course->image = $imgName;
        // $course->file = 'files/'.$filename;
        // $course->save();

        $course= Sop::findOrFail($id)->update([
            'company_id'        => $request->company_id,
            'organization_id'   => $request->organization_id ?? null,
            'title'             => $request->title,
            // 'image'             => '',
            'description'       => $request->description,
            // 'file'              => 'files/'.$filename,
        ]);

        // DB::commit();
        return response()->json([
            'success'=>$course,
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
     * Ini adalah sebuah method bernama destroy yang menerima parameter $id. Method ini digunakan untuk menghapus data dari tabel sops dengan id tertentu.
     * Pada baris pertama, terdapat sebuah fungsi destroy yang akan menghapus data dari tabel sops dengan id yang sesuai dengan parameter $id.
     * Pada baris terakhir, terdapat sebuah statement return yang mengembalikan sebuah response dalam bentuk JSON yang berisi pesan sukses.
     */
    public function destroy($id)
    {
        Sop::destroy($id);

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
