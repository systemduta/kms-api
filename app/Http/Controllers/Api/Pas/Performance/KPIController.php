<?php

namespace App\Http\Controllers\Api\Pas\Performance;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Pas_3P;
use App\Models\Pas_dimensi;
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
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id3p' => 'required', //3
                'idDimensi' => 'required', //12
                'idCompany' => 'required', //18
                'idDivisi' => 'required',  //177
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $name3P = Pas_3P::find($request->id3p);
            $nameDimensi = Pas_dimensi::find($request->idDimensi);
            $nameCompany = Company::find($request->idCompany);
            $nameDivisi = Organization::find($request->idDivisi);
            $datas = DB::table('pas_kpis')
                ->join('pas_3p', 'pas_kpis.3p_id', '=', 'pas_3p.id')
                ->join('pas_dimensis', 'pas_kpis.dimensi_id', '=', 'pas_dimensis.id')
                ->join('companies', 'pas_kpis.company_id', '=', 'companies.id')
                ->join('organizations', 'pas_kpis.division_id', '=', 'organizations.id')
                ->where('pas_kpis.3p_id', $request->id3p)
                ->where('pas_kpis.dimensi_id', $request->idDimensi)
                ->where('pas_kpis.company_id', $request->idCompany)
                ->where('pas_kpis.division_id', $request->idDivisi)
                ->select('pas_kpis.*')
                ->get();
            return response()->json(
                [
                    'name3p' => $name3P->name,
                    'nameDimensi' => $nameDimensi->name,
                    'nameCompany' => $nameCompany->name,
                    'nameDivisi' => $nameDivisi->name,
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
                'id3p' => 'required',
                'idDimensi' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'name' => 'required',
                'max_nilai' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_kpis')->insertGetId([
                '3p_id' => $request->id3p,
                'dimensi_id' => $request->idDimensi,
                'company_id' => $request->idCompany,
                'division_id' => $request->idDivisi,
                'name' => $request->name,
                'max_nilai' => $request->max_nilai,
            ]);
            return response()->json(
                [
                    'data' => $InsertGetId,
                    'message' => 'success',
                ],
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
            $datas = DB::table('pas_kpis')
                ->where('id', $id)
                ->first();
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
                'id3p' => 'required',
                'idDimensi' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'name' => 'required',
                'max_nilai' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_kpis')->where('id', $request->id)->update([
                '3p_id' => $request->id3p,
                'dimensi_id' => $request->idDimensi,
                'company_id' => $request->idCompany,
                'division_id' => $request->idDivisi,
                'name' => $request->name,
                'max_nilai' => $request->max_nilai,
            ]);
            return response()->json(
                [
                    'data' => $InsertGetId,
                    'message' => 'success',
                ],
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
