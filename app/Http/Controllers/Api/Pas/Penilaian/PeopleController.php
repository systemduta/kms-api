<?php

namespace App\Http\Controllers\Api\Pas\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Pas_dimensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

    public function getInd(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'id3p' => 'required',
                'kpi_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $nilaiA = DB::table('pas_ind_penilaians')
                    ->where('3p_id',$request->id3p)
                    ->where('kpi_id',$request->kpi_id)
                    ->where(function ($query) {
                        $query->where('grade', 'a')
                            ->orWhere('grade', 'A');
                    })
                    ->orderBy('nilai','desc')
                    ->get();
            $nilaiB = DB::table('pas_ind_penilaians')
                    ->where('3p_id',$request->id3p)
                    ->where('kpi_id',$request->kpi_id)
                    ->where(function ($query) {
                        $query->where('grade', 'b')
                            ->orWhere('grade', 'B');
                    })
                    ->orderBy('nilai','desc')
                    ->get();
            $nilaiC = DB::table('pas_ind_penilaians')
                    ->where('3p_id',$request->id3p)
                    ->where('kpi_id',$request->kpi_id)
                    ->where(function ($query) {
                        $query->where('grade', 'c')
                            ->orWhere('grade', 'C');
                    })
                    ->orderBy('nilai','desc')
                    ->get();
            return response()->json(
                [
                    'nilaiA' => $nilaiA,
                    'nilaiB' => $nilaiB,
                    'nilaiC' => $nilaiC,
                    'message' => 'success',
                ]
            );
            // $nilai4 = DB::table('pas_ind_penilaians')
            //     ->where('3p_id', $request->id3p)
            //     ->where('kpi_id', $request->kpi_id)
            //     ->where('nilai', 4)
            //     ->orderBy('grade', 'asc')
            //     ->get();
            // $nilai3 = DB::table('pas_ind_penilaians')
            //     ->where('3p_id', $request->id3p)
            //     ->where('kpi_id', $request->kpi_id)
            //     ->where('nilai', 3)
            //     ->orderBy('grade', 'asc')
            //     ->get();
            // $nilai2 = DB::table('pas_ind_penilaians')
            //     ->where('3p_id', $request->id3p)
            //     ->where('kpi_id', $request->kpi_id)
            //     ->where('nilai', 2)
            //     ->orderBy('grade', 'asc')
            //     ->get();
            // $nilai1 = DB::table('pas_ind_penilaians')
            //     ->where('3p_id', $request->id3p)
            //     ->where('kpi_id', $request->kpi_id)
            //     ->where('nilai', 1)
            //     ->orderBy('grade', 'asc')
            //     ->get();
            // return response()->json(
            //     [
            //         'nilai4' => $nilai4,
            //         'nilai3' => $nilai3,
            //         'nilai2' => $nilai2,
            //         'nilai1' => $nilai1,
            //         'message' => 'success',
            //     ]
            // );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    public function people()
    {
        try {
            $p3 = DB::table('pas_3p')->where('id',1)->first();
            $dimensi = DB::table('pas_dimensis')->where('3p_id', 1)->get();
            $kpi     = DB::table('pas_kpis')->where('3p_id', 1)->get();

            return response()->json(
                [
                    'p3' => $p3,
                    'dimensi' => $dimensi,
                    'kpi' => $kpi,
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Absen' => 'required',
                'Unity' => 'required',
                'Visi' => 'required',
                'Hati' => 'required',
                'Semangat' => 'required',
                'final_record' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }
            $data = $request->only(['Absen', 'Unity', 'Visi', 'Hati', 'Semangat', 'final_record']);

            $absen = $data['Absen'];
            $unity = $data['Unity'];
            $visi = $data['Visi'];
            $hati = $data['Hati'];
            $semangat = $data['Semangat'];
            $final_record = $data['final_record'];

            try {
                DB::beginTransaction();

                $bulan = Carbon::parse($absen['date'])->format('m');
                $tahun = Carbon::parse($absen['date'])->format('Y');
                $cekData = DB::table('pas_penilaian_absens')
                    ->where('user_id', $absen['user_id'])
                    ->where('dimensi_id', $absen['dimensi_id'])
                    ->whereMonth('date', $bulan)
                    ->whereYear('date', $tahun)
                    ->first();

                if ($cekData) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "User sudah dinilai pada bulan yang dipilih",
                    ], 422);
                } else {
                    $InsertGetId = DB::table('pas_penilaian_absens')->insertGetId([
                        'user_id' => $absen['user_id'],
                        'dimensi_id' => $absen['dimensi_id'],
                        'date' => $absen['date'],
                        'nilai' => $absen['nilaiAkhir'],
                        'max_nilai' => $absen['max_nilai'],
                    ]);

                    if ($InsertGetId) {
                        $insertToKpiAbsen = [];
                        foreach ($absen['detail'] as $item) {
                            $insertToKpiAbsen[] = [
                                'penilaianAbsen_id' => $InsertGetId,
                                'kpi_id' => $item['kpi_id'],
                                'nilai' => $item['value'],
                            ];
                        }

                        $InsertToKpiAbsen = DB::table('pas_kpi_absens')->insert($insertToKpiAbsen);

                        if (!$InsertToKpiAbsen) {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Gagal masuk ke database [code: BR1-500]',
                            ], 500);
                        }

                        $InsertToFinalRecord = DB::table('pas_final_record_3ps')->insertGetId([
                            'user_id' => $final_record['user_id'],
                            'id_3p' => $final_record['id_3p'],
                            'date' => $final_record['date'],
                            'nilai' => $final_record['nilai'],
                        ]);

                        if (!$InsertToFinalRecord) {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Gagal masuk ke database [code: D-A-500]',
                            ], 500);
                        }

                        // insert Unity
                        $insertUnity = [];
                        foreach ($unity as $item) {
                            $insertUnity[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                'date' => $item['date'],
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertUnity);

                        // insert Visi
                        $insertVisi = [];
                        foreach ($visi as $item) {
                            $insertVisi[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                'date' => $item['date'],
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertVisi);

                        // insert Hati
                        $insertHati = [];
                        foreach ($hati as $item) {
                            $insertHati[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                'date' => $item['date'],
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertHati);

                        // insert Semangat
                        $insertSemangat = [];
                        foreach ($semangat as $item) {
                            $insertSemangat[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                'date' => $item['date'],
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertSemangat);

                        DB::commit();

                        return response()->json([
                            'statusAbsen' => 'Data Absen tersimpan',
                            'statusUnity' => 'Data Unity tersimpan',
                            'statusVisi' => 'Data Visi tersimpan',
                            'statusHati' => 'Data Hati tersimpan',
                            'statusSemangat' => 'Data Semangat tersimpan',
                            'statusFinal' => 'Data Final tersimpan',
                            'statusCode' => 200
                        ]);
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Gagal masuk ke database [code: BR1-500]',
                        ], 500);
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => $e->getMessage(),
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    public function show(Request $request)
    {
        try {
            $bulan = Carbon::parse($request->date)->format('m');
            $tahun = Carbon::parse($request->date)->format('Y');
            $final_record = DB::table('pas_final_record_3ps')
                ->where('user_id', $request->user_id)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->first();
            $penilaian_absen = DB::table('pas_penilaian_absens')
                ->where('user_id', $request->user_id)
                ->where('dimensi_id', $request->id_dimensi)
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->first();
            $penilaian_kpi_absen = DB::table('pas_kpi_absens')
                ->where('penilaianAbsen_id', $penilaian_absen->id)
                ->get();
            return response()->json(
                [
                    'final_record' => $final_record,
                    'penilaian_absen' => $penilaian_absen,
                    'penilaian_kpi_absen' => $penilaian_kpi_absen,
                    'statusCode' => 200
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
