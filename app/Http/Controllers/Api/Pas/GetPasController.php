<?php

namespace App\Http\Controllers\Api\Pas;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GetPasController extends Controller
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
                'nikUser' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $getUserID = DB::table('users')->where('nik', $request->nikUser)->first();
            $bulan = Carbon::parse($request->date)->format('m');
            $tahun = Carbon::parse($request->date)->format('Y');

            $finalSkor = DB::table('pas_final_skors')
                ->where('user_id', $getUserID->id)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->first();
            $detailSkor3p = DB::table('pas_final_record_3ps')
                ->leftJoin('pas_3p', 'pas_final_record_3ps.id_3p', '=', 'pas_3p.id')
                ->where('user_id', $getUserID->id)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->select('pas_final_record_3ps.*', 'pas_3p.name as name3p')
                ->get();

            foreach ($detailSkor3p as $key) {
                if ($key->id_3p == 1) {
                    $dimensis = DB::table('pas_dimensis')
                        ->where('3p_id', 1)
                        ->select('id', 'name')
                        ->get();
                    $key->detail = [];
                    foreach ($dimensis as $dimensi) {
                        $key->detail[$dimensi->name] = (object)[];
                        if ($dimensi->id == 1) {
                            $absen = DB::table('pas_penilaian_absens')
                                ->join('users', 'pas_penilaian_absens.user_id', '=', 'users.id')
                                ->join('pas_dimensis', 'pas_penilaian_absens.dimensi_id', '=', 'pas_dimensis.id')
                                ->where('pas_penilaian_absens.user_id', $getUserID->id)
                                ->whereMonth('pas_penilaian_absens.date', $bulan)
                                ->whereYear('pas_penilaian_absens.date', $tahun)
                                ->select('pas_penilaian_absens.*', 'pas_dimensis.name as nameDimensi')
                                ->first();
                            $detailAbsen = DB::table('pas_kpi_absens')
                                ->join('pas_kpis', 'pas_kpi_absens.kpi_id', '=', 'pas_kpis.id')
                                ->where('penilaianAbsen_id', $absen->id)
                                ->select('pas_kpi_absens.*', 'pas_kpis.name as nameKPI')
                                ->get();
                            $absen->detailAbsen = $detailAbsen;
                            $key->detail[$dimensi->name] = $absen;
                        }
                        if ($dimensi->id == 2) {
                            $unity = DB::table('pas_penilaian_others')
                                ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                ->join('pas_kpis', 'pas_dimensis.id', '=', 'pas_kpis.dimensi_id')
                                ->where('pas_penilaian_others.user_id', $getUserID->id)
                                ->whereMonth('pas_penilaian_others.date', $bulan)
                                ->whereYear('pas_penilaian_others.daPte', $tahun)
                                ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                ->get();
                            $key->detail[$dimensi->name] = $unity;
                        }
                    }
                }
            }



            return response()->json([
                'finalSkor' => $finalSkor,
                'detailSkor3p' => $detailSkor3p,
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403
            ], 403);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
