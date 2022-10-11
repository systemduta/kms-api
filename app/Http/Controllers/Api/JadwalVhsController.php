<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwalvhs;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalVhsController extends Controller
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
    }

    public function sop_all(){
        try {
            return response()->json(['data' => Jadwalvhs::orderBy("id",'DESC')->get()]);
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
    public function store(Request $request)
    {
        // error_reporting(0);
        $validator = Validator::make($request->all(),[
            'name'              => 'required',
            'batch'             => 'required',
            'start'             => 'required',
            'end'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        $auth   = auth()->user();

        try {
            DB::beginTransaction();
            $JadwalGetId = DB::table('jadwalvhs')->insertGetId([
                'name'        => $request->name,
                'batch'       => $request->batch,
                'start'       => $request->start,
                'end'         => $request->end,
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
        $data = DB::table('jadwalvhs')->where('id',$id)->first();
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
        $name = $request->name;
        $batch = $request->batch;
        $start = $request->start;

        $jadwal               = Jadwalvhs::find($id);
        $jadwal->name         = $name;
        $jadwal->batch       = $batch;
        $jadwal->start        = $start;
        $jadwal->save();

        try {
            $jadwal               = Jadwalvhs::find($id);
        $jadwal->name         = $name;
        $jadwal->batch       = $batch;
        $jadwal->start        = $start;
        $jadwal->save();

        return response()->json([
            'success'=>$jadwal,
            'message'=>'update successfully'],
        $this->successStatus);
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
        $idDestroy =Jadwalvhs::find($id);
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
