<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vhs_certi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VhsCertiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = DB::table('vhs_certis')
                ->join('users','users.id','=','vhs_certis.user_id')
                ->select('vhs_certis.*','users.id as idUser','users.name as usersname')
                ->get();
            return response()->json([
                'Message'   => 'success',
                'Data'      => $data,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if ($request->hasFile('doc1')) {
            $fileEXT    = $request->file('doc1')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc1')->getClientOriginalExtension();
            $doc1       = $filename. '_'.time().'.' .$EXT;
            $path1      = $request->file('doc1')->move(public_path('file/certivhs/doc1'), $doc1);
        }else {
            $doc1 = 'not found';
        }

        if ($request->hasFile('doc2')) {
            $fileEXT    = $request->file('doc2')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc2')->getClientOriginalExtension();
            $doc2       = $filename. '_'.time().'.' .$EXT;
            $path2      = $request->file('doc2')->move(public_path('file/certivhs/doc2'), $doc2);
        }else {
            $doc2 = 'not found';
        }

        if ($request->hasFile('doc3')) {
            $fileEXT    = $request->file('doc3')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc3')->getClientOriginalExtension();
            $doc3       = $filename. '_'.time().'.' .$EXT;
            $path3      = $request->file('doc3')->move(public_path('file/certivhs/doc3'), $doc3);
        }else {
            $doc3 = 'not found';
        }        

        try {
            $data= Vhs_certi::create([
                'user_id'   => $request->user_id,
                'doc1'      => $doc1,
                'doc2'      => $doc2,
                'doc3'      => $doc3,
            ]);
            return response()->json(
                [
                    'data' => "saved successfully ".$data
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
            $data = DB::table('vhs_certis')
                ->join('users','users.id','=','vhs_certis.user_id')
                ->select('vhs_certis.*','users.id as idUser','users.name as usersname')
                ->where('vhs_certis.id',$id)
                ->first();
            return response()->json([
                'Message'   => 'success',
                'Data'      => $data,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if ($request->hasFile('doc1')) {
            $fileEXT    = $request->file('doc1')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc1')->getClientOriginalExtension();
            $doc1       = $filename. '_'.time().'.' .$EXT;
            $path1      = $request->file('doc1')->move(public_path('file/certivhs/doc1'), $doc1);

                $data= Vhs_certi::findOrFail($id)->update([
                    'user_id'   => $request->user_id,
                    'doc1'      => $doc1,
                ]);
        }

        if ($request->hasFile('doc2')) {
            $fileEXT    = $request->file('doc2')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc2')->getClientOriginalExtension();
            $doc2       = $filename. '_'.time().'.' .$EXT;
            $path2      = $request->file('doc2')->move(public_path('file/certivhs/doc2'), $doc2);
            
                $data= Vhs_certi::findOrFail($id)->update([
                    'user_id'   => $request->user_id,
                    'doc2'      => $doc2,
                ]);
        }

        if ($request->hasFile('doc3')) {
            $fileEXT    = $request->file('doc3')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('doc3')->getClientOriginalExtension();
            $doc3       = $filename. '_'.time().'.' .$EXT;
            $path3      = $request->file('doc3')->move(public_path('file/certivhs/doc3'), $doc3);

                $data= Vhs_certi::findOrFail($id)->update([
                    'user_id'   => $request->user_id,
                    'doc3'      => $doc3, 
                ]);
        } 
        if ($request->user_id) {
            try {
                $data= Vhs_certi::findOrFail($id)->update([                    
                    'user_id'   => $request->user_id,
                ]);
                return response()->json(
                    [
                        'data' => "updated successfully ".$data
                    ]
                );
            } catch (\Throwable $th) {
                return response()->json([
                    'message'   => $th->getMessage(),
                ]);
            }
        }
        else {
            return response()->json([
                'message'   => "error",
            ]);
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
            $delete = Vhs_certi::findOrFail($id);
            $delete->delete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
