<?php

namespace App\Http\Controllers\Api\Pas\People;

use App\Http\Controllers\Controller;
use App\Models\Pas_dimensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DimensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_per_3p($id)
    {
        try {
            $datas = DB::table('pas_dimensis as d')
                ->join('pas_3p as p', 'd.3p_id', '=', 'p.id')
                ->select('d.id','p.id as pas_3p_id', 'd.name', 'p.name as name_3p')
                ->where('d.3p_id', $id)
                ->get();
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

    public function index()
    {
        try {
            $datas = DB::table('pas_dimensis as d')
                ->join('pas_3p as p', 'd.3p_id', '=', 'p.id')
                ->select('d.id','d.3p_id as id_3p', 'd.name', 'p.name as name_3p')
                ->get();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_3p' => 'required',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_dimensis')->insertGetId([
                '3p_id' => $request->id_3p,
                'name' => $request->name,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = DB::table('pas_dimensis')
                ->select('id', '3p_id as id_3p', 'name', 'created_at', 'updated_at')
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'id_3p' => 'required',
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $datas = DB::table('pas_dimensis')->where('id', $request->id)->update([
                '3p_id' => $request->id_3p,
                'name' => $request->name,
            ]);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Pas_dimensi::findOrFail($id);
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
