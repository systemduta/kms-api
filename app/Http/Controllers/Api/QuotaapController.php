<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwalvhs;
use App\Models\QuotaAP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotaapController extends Controller
{
    public $successStatus = 200; //variabel ayng akan dipangggil saat operasi sukses dilakukan
    public $errorStatus = 403; //variabel yang akan di panggil saat operasi gagal dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAll($id)
    {
        $data = DB::table('jadwalvhs')
                ->join('quotaaps','quotaaps.jadwal_id','=','jadwalvhs.id')
                ->join('companies','companies.id','=','quotaaps.company_id')
                ->select('quotaaps.id','companies.id as comid','companies.name as comname','jadwalvhs.id as jadwalid','jadwalvhs.name','jadwalvhs.batch','quotaaps.quota')
                ->where('jadwalvhs.id',$id)
                ->get();
        return response()->json([
                    'data'=>$data,
                    'message'=>'get data successfully'],
                $this->successStatus);     
    }

    public function getJadwal($id)
    {
        $data = Jadwalvhs::where('id',$id)->first();
        return response()->json([
            'success'=>$data,
            'message'=>'get data successfully'],
        $this->successStatus);
    }

    public function index()
    {
        $data = DB::table('quotaaps')->get();
        return response()->json([
            'success'=>$data,
            'message'=>'update successfully'],
        $this->successStatus);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'jadwal_id'     =>'required',
            'company_id'    => 'required',
            'quota'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }
        try {
            $tokenUser = DB::table('users')
                    ->join('organizations','organizations.id','users.organization_id')
                    ->where('organizations.is_str','=','1')
                    ->where('users.company_id','=',$request->company_id)
                    ->where('token','!=',"")
                    ->pluck('token')
                    ->toArray();
            $cek = DB::table('quotaaps')->where('company_id',$request->company_id)->where('jadwal_id',$request->jadwal_id)->count();
            if ($cek) {
                return response()->json([
                    'message'=>'data perusahaan sudah ada'],
                $this->errorStatus);
            } else {
                $data = DB::table('quotaaps')->insertGetId([
                    "jadwal_id"=>$request->jadwal_id,
                    "company_id"=>$request->company_id,
                    "quota"=>$request->quota,
                ]);
                if($tokenUser) {
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
                    'success'=>$data,
                    'message'=>'insert successfully'],
                $this->successStatus);
            }         
        } catch (\Exception $th) {
            return response()->json([
                'success'=>$th->getMessage(),],
            $this->errorStatus);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = QuotaAP::where('jadwal_id',$id)->get();
            return response()->json([
                'success'=>$data,
                'message'=>'get successfully'],
            $this->successStatus);
        } catch (\Exception $th) {
            return response()->json([
                'success'=>$th->getMessage(),],
            $this->errorStatus);
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'id'     =>'required',
            'quotaAp'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        try {
            $tokenUser = DB::table('users')
                    ->join('organizations','organizations.id','users.organization_id')
                    ->where('organizations.is_str','=','1')
                    ->where('users.company_id','=',$request->company_id)
                    ->where('token','!=',"")
                    ->pluck('token')
                    ->toArray();
            $datas = json_decode($request->quotaAp);                
            QuotaAP::where('jadwal_id', $id)->delete();
            foreach ($datas as $data ) {
                $getsuk = QuotaAP::where('jadwal_id', $id)->where('company_id',$data->id)->updateOrCreate(['jadwal_id' => $id, 'company_id' => $data->id],
                ['quota' => $data->quota]); 
            }
            if($tokenUser) {
                $result = fcm()->to($tokenUser)
                ->timeToLive(0)
                ->priority('high')
                ->notification([
                    'title' => 'Hai, ada update jadwal 1VHS nih segera daftarkan teman perusahaan mu!',
                    'body' => 'Silahkan buka website admin',
                ])
                ->data([
                    'title' => 'Hai, ada update jadwal 1VHS nih segera daftarkan teman perusahaan mu!',
                    'body' => 'Silahkan buka website admin',
                ])
                ->send();
            }            
            return response()->json([
                'success'=>'berhasil',
                'message'=>'update successfully'],
            $this->successStatus);
        } catch (\Exception $th) {
            return response()->json([
                'message'=>$th->getMessage()],
            $this->errorStatus);
        }
    }

    public function singleUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'jadwal_id'=> 'required',
            'company_id'=> 'required',
            'quota'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }
        try {
            $cek = DB::table('jadwal_user_vhs')
                    ->where('jadwal_id',$request->jadwal_id_lama)
                    ->where('company_id',$request->company_id_lama)
                    ->count();
            if($cek>0)
            {
                return response()->json([
                    'message'=>"Ada user yang telah didaftarkan hapus terlebih dahulu"],
                $this->errorStatus);
            } else{
                $tokenUser = DB::table('users')
                        ->join('organizations','organizations.id','users.organization_id')
                        ->where('organizations.is_str','=','1')
                        ->where('token','!=',"")
                        ->pluck('token')
                        ->toArray();
                $id = DB::table('quotaaps')->where('id',$id)->update([
                    'jadwal_id' => $request->jadwal_id,
                    'company_id' => $request->company_id,
                    'quota' => $request->quota,
                ]);
                if($tokenUser) {
                    $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
                    ->notification([
                        'title' => 'Hai, ada update jadwal 1VHS nih segera daftarkan teman perusahaan mu!',
                        'body' => 'Silahkan buka website admin',
                    ])
                    ->data([
                        'title' => 'Hai, ada update jadwal 1VHS nih segera daftarkan teman perusahaan mu!',
                        'body' => 'Silahkan buka website admin',
                    ])
                    ->send();
                }
                return response()->json([
                    'success'=>$id,
                    'message'=>'update successfully'],
                $this->successStatus);
            }            
        } catch (\Exception $th) {
            return response()->json([
                'message'=>$th->getMessage()],
            $this->errorStatus);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $idDestroy =QuotaAP::find($id);
        if ($idDestroy) {            
            $cekUser = DB::table('jadwal_user_vhs')
                        ->where('jadwal_id', $idDestroy->jadwal_id)
                        ->where('company_id', $idDestroy->company_id)
                        ->count();
            if ($cekUser!=0) {                
                return response()->json(['error' => 'Ada user yang terdaftar, Hapus terlebih dahulu'], $this->errorStatus);
            } else {            
                QuotaAP::destroy($id);
                return response()->json([
                    'message' => 'Data Berhasil di Hapus'
                ]);
            }          
        } else {
            return response()->json(['error' => "data not delete yet"], $this->errorStatus);
        }
    }
}
