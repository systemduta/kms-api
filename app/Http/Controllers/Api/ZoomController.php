<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwalvhs;
use App\Models\ZoomsVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpKernel\Exception\HttpException;

class ZoomController extends Controller
{
    
    public $successStatus = 200;
    public $errorStatus =403;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=DB::table('zooms_vhs')
                    ->join('jadwalvhs','jadwalvhs.id','zooms_vhs.jadwal_id')
                    ->select('zooms_vhs.id as zoom_id','jadwalvhs.id as jadwalvhs_id','zooms_vhs.name as zoom_name','jadwalvhs.name as jadwalvhs_name','zooms_vhs.*','jadwalvhs.*')
                    ->orderBy('zooms_vhs.id', 'desc')
                    ->get();
            return response()->json(
                [
                    'data' => $data
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    public function getvhs()
    {
        try {
            $user = auth()->user();
            $data=DB::table('jadwalvhs')
                ->select('id','name','batch','start')
                ->when(($user && $user->role!=1), function ($q) use ($user) {
                        return $q->where('id', $user->company_id);
                    })
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
            'jadwal_id'             => 'required',
            'name'                  => 'required',
            'times'                 => 'required',
            'link'                  => 'required',
            'meeting_id'            => 'required',
            'password'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
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
    public function show($id)
    {
        // $data = DB::table('jadwalvhs')->where('id',$id)->first();
        $data=DB::table('zooms_vhs')
                ->join('jadwalvhs','jadwalvhs.id','zooms_vhs.jadwal_id')
                ->select('zooms_vhs.id as zoom_id','jadwalvhs.id as jadwalvhs_id','zooms_vhs.name as zoom_name','jadwalvhs.name as jadwalvhs_name','zooms_vhs.*','jadwalvhs.*')
                ->where('zooms_vhs.id',$id)
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
    public function update(Request $request, $id)
    {
        $jadwal_id=$request->jadwal_id;
        $name=$request->name;
        $times=$request->times;
        $link=$request->link;
        $meeting_id=$request->meeting_id;
        $password=$request->password;

        try {
            $zoom=ZoomsVhs::findOrfail($id)->update([
                'jadwal_id'         => $jadwal_id,
                'name'              => $name,
                'times'             => $times,
                'link'              => $link,
                'meeting_id'        => $meeting_id,
                'password'          => $password,
            ]);
         return response()->json([
            'success'=>$zoom,
            'message'=>'update successfully'],

        $this->successStatus);
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
    public function destroy($id)
    {
        try {
            ZoomsVhs::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }
}
