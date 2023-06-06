<?php

namespace App\Http\Controllers\Api\Pas\Pengaturan\Performance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndPenilaianController extends Controller
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
                'id3p' => 'required',
                'idKpi' => 'required',
                'idDimensi' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $name3P = Pas_3P::find($request->id3p);
            $nameDimensi = Pas_dimensi::find($request->idDimensi);
            $nameKpi = Pas_kpi::find($request->idKpi);
            $nameCompany = Company::find($request->idCompany);
            $nameDivisi = Organization::find($request->idDivisi);

            $datas = DB::table('pas_ind_penilaians')
                ->where('3p_id', $request->id3p)
                ->where('kpi_id', $request->idKpi)
                ->where('company_id', $request->idCompany)
                ->where('division_id', $request->idDivisi)
                ->orderBy('nilai', 'desc')->orderBy('grade', 'asc')
                ->get();
            return response()->json(
                [
                    'name3p' => $name3P->name,
                    'nameDimensi' => $nameDimensi->name,
                    'nameKpi' => $nameKpi->name,
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
                'idKpi' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'nilai' => 'required',
                'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_ind_penilaians')->insertGetId([
                '3p_id' => $request->id3p,
                'kpi_id' => $request->idKpi,
                'company_id' => $request->idCompany,
                'division_id' => $request->idDivisi,
                'nilai' => $request->nilai,
                'grade' => $request->grade,
                'desc' => $request->desc,
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
            $datas = DB::table('pas_ind_penilaians')
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
                'idKpi' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'nilai' => 'required',
                'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_ind_penilaians')->where('id', $request->id)->update([
                '3p_id' => $request->id3p,
                'kpi_id' => $request->idKpi,
                'company_id' => $request->idCompany,
                'division_id' => $request->idDivisi,
                'nilai' => $request->nilai,
                'grade' => $request->grade,
                'desc' => $request->desc,
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
            $delete = Pas_ind_penilaian::findOrFail($id);
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
