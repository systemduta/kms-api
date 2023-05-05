<?php

namespace App\Http\Controllers\Api\Pas\People;

use App\Http\Controllers\Controller;
use App\Models\Pas_kpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_per_dimensi($id)
    {
        try {
            $userData = auth()->user();
            $datas = DB::table('pas_kpis')
                ->join('pas_3p', 'pas_kpis.3p_id', '=', 'pas_3p.id')
                ->join('pas_dimensis', 'pas_kpis.dimensi_id', '=', 'pas_dimensis.id')
                ->leftjoin('companies', 'pas_kpis.company_id', '=', 'companies.id')
                ->leftjoin('organizations', 'pas_kpis.division_id', '=', 'organizations.id')
                ->when($userData->role!=1, function ($q) use ($userData) {
                    return $q->where('pas_kpis.company_id', $userData->company_id);
                })
                ->where('pas_kpis.dimensi_id', $id)
                ->select('pas_kpis.id', 'pas_3p.name as name_3p', 'pas_dimensis.name as name_dimensi', 'companies.name as name_company','organizations.name as name_organization','pas_kpis.name','pas_kpis.max_nilai', 'pas_kpis.created_at', 'pas_kpis.updated_at')
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
