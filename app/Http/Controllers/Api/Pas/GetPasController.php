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
                return response()->json(['message' => $validator->errors(), 'statusCode'=>401], 401);
            }
            $nikUser = $request->nikUser;
            $results = DB::select("SELECT * FROM users WHERE nik LIKE ?", ["%$nikUser%"]);
            $user = !empty($results) ? $results[0] : null;
            if ($user==null) {
                return response()->json([
                    'message' => 'User tidak ditemukan',
                    'statusCode' => 403,
                ], 403);
            } else{
                $bulan = Carbon::parse($request->date)->format('m');
                $tahun = Carbon::parse($request->date)->format('Y');

                $cekData = DB::table('pas_final_skors')
                            ->where('user_id',$user->id)
                            ->whereMonth('date',$bulan)
                            ->whereYear('date',$tahun)
                            ->count();
                if ($cekData) {
                    $finalSkor = DB::table('pas_final_skors')
                                ->where('user_id', $user->id)
                                ->whereMonth('date', $bulan)
                                ->whereYear('date', $tahun)
                                ->first();
                    $detailSkor3p = DB::table('pas_final_record_3ps')
                                ->leftJoin('pas_3p', 'pas_final_record_3ps.id_3p', '=', 'pas_3p.id')
                                ->where('user_id', $user->id)
                                ->whereMonth('date', $bulan)
                                ->whereYear('date', $tahun)
                                ->select('pas_final_record_3ps.*', 'pas_3p.name as name3p', 'pas_3p.persentase')
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
                                        ->where('pas_penilaian_absens.user_id', $user->id)
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
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_dimensis.id', 2)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
                                    $key->detail[$dimensi->name] = $unity;
                                }
                                if ($dimensi->id == 3) {
                                    $visi = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_dimensis.id', 3)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
                                    $key->detail[$dimensi->name] = $visi;
                                }
                                if ($dimensi->id == 4) {
                                    $hati = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 4)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $hati;
                                }
                                if ($dimensi->id == 5) {
                                    $semangat = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 5)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $semangat;
                                }
                            }
                        }
                        if ($key->id_3p == 2) {
                            $dimensis = DB::table('pas_dimensis')
                                ->where('3p_id', 2)
                                ->select('id', 'name')
                                ->get();
        
                            $key->detail = [];
                            foreach ($dimensis as $dimensi) {
                                $key->detail[$dimensi->name] = (object)[];
                                if ($dimensi->id == 6) {
                                    $routine = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 6)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $routine;
                                }
                                if ($dimensi->id == 7) {
                                    $cross_function = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 7)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $cross_function;
                                }
                                if ($dimensi->id == 8) {
                                    $interaction = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 8)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $interaction;
                                }
                            }
                        }
                        if ($key->id_3p == 3) {
                            $dimensis = DB::table('pas_dimensis')
                                ->where('3p_id', 3)
                                ->select('id', 'name')
                                ->get();
        
                            $key->detail = [];
                            foreach ($dimensis as $dimensi) {
                                $key->detail[$dimensi->name] = (object)[];
                                if ($dimensi->id == 9) {
                                    $finance = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 9)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $finance;
                                }
                                if ($dimensi->id == 10) {
                                    $daya_saing = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 10)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $daya_saing;
                                }
                                if ($dimensi->id == 11) {
                                    $kepuasan_konsumen = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 11)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $kepuasan_konsumen;
                                }
                                if ($dimensi->id == 12) {
                                    $kapasitas_karyawan = DB::table('pas_penilaian_others')
                                        ->join('pas_dimensis', 'pas_penilaian_others.dimensi_id', '=', 'pas_dimensis.id')
                                        ->join('pas_kpis', 'pas_penilaian_others.kpi_id', '=', 'pas_kpis.id')
                                        ->where('pas_penilaian_others.dimensi_id', 12)
                                        ->where('pas_penilaian_others.user_id', $user->id)
                                        ->whereMonth('pas_penilaian_others.date', $bulan)
                                        ->whereYear('pas_penilaian_others.date', $tahun)
                                        ->select('pas_penilaian_others.*', 'pas_kpis.name as nameKpi')
                                        ->get();
        
                                    $key->detail[$dimensi->name] = $kapasitas_karyawan;
                                }
                            }
                        }
                    }
                    return response()->json([
                        'detailUser' => $user,
                        'finalSkor' => $finalSkor,
                        'detailSkor3p' => $detailSkor3p,
                        'statusCode' => 200,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'data tidak ditemukan',
                        'statusCode' => 403,
                    ], 403);
                }                
            }            
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
