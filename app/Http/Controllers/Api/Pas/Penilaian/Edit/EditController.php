<?php
//URUNG show edit blm 
namespace App\Http\Controllers\Api\Pas\Penilaian\Edit;

use App\Http\Controllers\Controller;
use App\Models\Pas\Pas_final_record_3p;
use App\Models\Pas\Pas_final_skors;
use App\Models\Pas\Pas_kpi_absens;
use App\Models\Pas\Pas_penilaian_absens;
use App\Models\Pas\Pas_penilaian_others;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EditController extends Controller
{
    // date
    // iduser;
    // idcompany
    // iddivisi
    public function cekData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idUser' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(),'statusCode'=>400], 400);
            }

            $bulan = Carbon::parse($request->date)->format('m');
            $tahun = Carbon::parse($request->date)->format('Y');
            $cek = DB::table('pas_final_skors')
                    ->where('user_id',$request->idUser)
                    ->whereMonth('date',$bulan)
                    ->whereYear('date',$tahun)
                    ->count();
            return response()->json([
                'data' => $cek, 
                'message' => 'sukses mendapatkan data',
                'statusCode' => 200 
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message'=> $e->getMessage(),
                'statusCode'=> 400,
            ],400);
        }
    }
    public function showPerDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idUser' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(),'statusCode'=>400], 400);
            }

            $dataUser = DB::table('users')->where('id',$request->idUser)->first();
            
            $getMonthUser = DB::table('pas_final_skors')
                            ->where('user_id',$request->idUser)
                            ->orderBy('date','asc')
                            ->get();
            return response()->json([
                'dataUser' => $dataUser,
                'dataBulan' => $getMonthUser,
                'message' => 'sukses get data',
                'statusCode' => 200,
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'message'=>$e->getMessage(),
                'statusCode'=>500
            ],500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'idUser' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $bulan = Carbon::parse($request->date)->format('m');
            $tahun = Carbon::parse($request->date)->format('Y');

            $dataUser = User::where('id', $request->idUser)->first();
            $finalSkor = DB::table('pas_final_skors')
                ->where('user_id', $request->idUser)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->first();
            $detailSkor3p = DB::table('pas_final_record_3ps')
                ->leftJoin('pas_3p', 'pas_final_record_3ps.id_3p', '=', 'pas_3p.id')
                ->where('user_id', $request->idUser)
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
                                ->where('pas_penilaian_absens.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                                ->where('pas_penilaian_others.user_id', $request->idUser)
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
                'detailUser' => $dataUser,
                'finalSkor' => $finalSkor,
                'detailSkor3p' => $detailSkor3p,
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'detailUser' => 'required',
                'finalSkor' => 'required',
                'detailSkor3p' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'statusCode' => 400], 400);
            }
            $data = $request->only(['detailUser', 'finalSkor', 'detailSkor3p']);
            $detailSkor = $data['detailSkor3p'];
            $finalskor = $data['finalSkor'];
            DB::beginTransaction();
            try {
                Pas_final_skors::findOrfail($finalskor['id'])->update(['nilai'=>$finalskor['nilai']]);
                //simpan nilai people
                //Absen                
                $update_tbl_final_record_3ps = Pas_final_record_3p::findOrfail($detailSkor[0]['id'])
                    ->update([
                        'nilai' => $detailSkor[0]['nilai']
                    ]);
                if ($update_tbl_final_record_3ps) {
                    $update_tbl_penilaian_absens = Pas_penilaian_absens::findOrfail($detailSkor[0]['detail']['Absen']['id'])->update(['nilai' => $detailSkor[0]['detail']['Absen']['nilai']]);
                }
                $detailAbsen = $detailSkor[0]['detail']['Absen'];
                //simpan kpi absen
                if ($update_tbl_penilaian_absens) {
                    foreach ($detailAbsen['detailAbsen'] as $item) {
                        Pas_kpi_absens::findOrfail($item['id'])->update(['nilai' => $item['nilai']]);
                    }
                }
                //simpan people yang lain
                if ($update_tbl_final_record_3ps) {
                    $categories = ['Unity', 'Visi', 'Hati', 'Semangat'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[0]['detail'][$category];

                        if ($details) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->update(['nilai' => $detail['nilai']]);
                            }
                        }
                    }
                }


                // Simpan nilai process
                $update_tbl_final_record_3ps_2 = Pas_final_record_3p::findOrFail($detailSkor[1]['id'])
                    ->update(['nilai' => $detailSkor[1]['nilai']]);

                if ($update_tbl_final_record_3ps_2) {
                    $categories = ['Routine', 'Cross Function', 'Interaction'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[1]['detail'][$category];

                        if ($details) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->update(['nilai' => $detail['nilai']]);
                            }
                        }
                    }
                }

                // Simpan nilai performance
                $update_tbl_final_record_3ps_3 = Pas_final_record_3p::findOrFail($detailSkor[2]['id'])
                    ->update(['nilai' => $detailSkor[2]['nilai']]);

                if ($update_tbl_final_record_3ps_3) {
                    $categories = ['Finance', 'Daya saing', 'Kepuasan Konsumen', 'Kapasitas Karyawan'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[2]['detail'][$category];

                        if (count($details) > 0) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->update(['nilai' => $detail['nilai']]);
                            }
                        }
                    }
                }

                
                DB::commit();
                return response()->json([
                    'message' => 'sukses update data',
                    'statusCode' => 200,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => $e->getMessage(),
                    'statusCode' => 403
                ], 403);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error ' + $e->getMessage(),
                'statusCode' => 500,
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'detailUser' => 'required',
                'finalSkor' => 'required',
                'detailSkor3p' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'statusCode' => 400], 400);
            }
            $data = $request->only(['detailUser', 'finalSkor', 'detailSkor3p']);
            $detailSkor = $data['detailSkor3p'];
            $finalskor = $data['finalSkor'];
            DB::beginTransaction();
            try {
                Pas_final_skors::findOrfail($finalskor['id'])->delete();

                //hapus nilai people
                //Absen                
                $detailAbsen = $detailSkor[0]['detail']['Absen'];
                //hapus kpi absen
                foreach ($detailAbsen['detailAbsen'] as $item) {
                    Pas_kpi_absens::findOrfail($item['id'])->delete();
                }
                $update_tbl_penilaian_absens = Pas_penilaian_absens::findOrfail($detailSkor[0]['detail']['Absen']['id'])->delete();

                if ($update_tbl_penilaian_absens) {
                    $update_tbl_final_record_3ps = Pas_final_record_3p::findOrfail($detailSkor[0]['id'])->delete();
                }

                //hapus people yang lain
                if ($update_tbl_final_record_3ps) {
                    $categories = ['Unity', 'Visi', 'Hati', 'Semangat'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[0]['detail'][$category];

                        if ($details) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->delete();
                            }
                        }
                    }
                }

                // hapus nilai process
                $update_tbl_final_record_3ps_2 = Pas_final_record_3p::findOrFail($detailSkor[1]['id'])
                    ->delete();

                if ($update_tbl_final_record_3ps_2) {
                    $categories = ['Routine', 'Cross Function', 'Interaction'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[1]['detail'][$category];

                        if ($details) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->delete();
                            }
                        }
                    }
                }

                // hapus nilai performance
                $update_tbl_final_record_3ps_3 = Pas_final_record_3p::findOrFail($detailSkor[2]['id'])
                    ->delete();

                if ($update_tbl_final_record_3ps_3) {
                    $categories = ['Finance', 'Daya saing', 'Kepuasan Konsumen', 'Kapasitas Karyawan'];

                    foreach ($categories as $category) {
                        $details = $detailSkor[2]['detail'][$category];

                        if (count($details) > 0) {
                            foreach ($details as $detail) {
                                Pas_penilaian_others::findOrFail($detail['id'])
                                    ->delete();
                            }
                        }
                    }
                }

                
                DB::commit();
                return response()->json([
                    'message' => 'sukses delete data',
                    'statusCode' => 200,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => $e->getMessage(),
                    'statusCode' => 403,
                ], 403);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error ' + $e->getMessage(),
                'statusCode' => 500,
            ], 500);
        }
    }
}
