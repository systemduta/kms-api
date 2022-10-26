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

    public function getCompany() {
        try {
            $data = DB::table('companies')
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

        try {
            DB::beginTransaction();
            $JadwalGetId = DB::table('jadwal_user_vhs')->insertGetId([
                'jadwal_id'         => $request->jadwal_id,
                'company_id'        => $request->company_id,
                'user_id'           => $request->user_id,
                'is_take'           => 0,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $JadwalGetId,
            'message'   => 'Data Berhasil disimpan!'
        ],200);
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
