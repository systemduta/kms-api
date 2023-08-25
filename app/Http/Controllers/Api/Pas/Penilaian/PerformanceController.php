<?php

namespace App\Http\Controllers\Api\Pas\Penilaian;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    public function performance(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id3p' => 'required',
                'idUser' => 'required',
                'idDivisi' => 'required',
                'idCompany' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $p3 = DB::table('pas_3p')->where('id', $request->id3p)->first();
            $dimensi = DB::table('pas_dimensis')->where('3p_id', $request->id3p)->get();
            $kpi     = DB::table('pas_kpis')
                ->where('3p_id', $request->id3p)
                ->where('company_id', $request->idCompany)
                ->where('division_id', $request->idDivisi)
                ->get();

            return response()->json(
                [
                    'p3' => $p3,
                    'dimensi' => $dimensi,
                    'kpi' => $kpi,
                    'message' => 'success',
                    'statusCode' => '200'
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
                'final_record' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }
            $data = $request->only(['Finance', 'Daya saing', 'Kepuasan Konsumen', 'Kapasitas Karyawan', 'final_record']);
            $finance = $data['Finance'];
            $daya_saing = $data['Daya saing'];
            $kepuasan_konsumen = $data['Kepuasan Konsumen'];
            $kapasitas_karyawan = $data['Kapasitas Karyawan'];
            $final_record = $data['final_record'];

            try {
                DB::beginTransaction();
                $carbonDate = Carbon::createFromFormat('Y-m', $final_record['date']);
                // $bulan = Carbon::parse($absen['date'])->format('m');
                $bulan = $carbonDate->format('m');
                // $tahun = Carbon::parse($absen['date'])->format('Y');
                $tahun = $carbonDate->format('Y');

                $cekData = DB::table('pas_final_record_3ps')
                    ->where('user_id', $final_record['user_id'])
                    ->where('id_3p', $final_record['id_3p'])
                    ->whereMonth('date', $bulan)
                    ->whereYear('date', $tahun)
                    ->first();

                if ($cekData) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "User sudah dinilai pada bulan yang dipilih",
                    ], 422);
                } else {
                    $carbonDate->day = 1;
                    $date = $carbonDate->format('Y-m-d');
                    if (count($finance) > 0) {
                        $insertFinance = [];
                        foreach ($finance as $item) {
                            $insertFinance[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                // 'date' => $item['date'],
                                'date' => $date,
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertFinance);
                    }
                    if (count($daya_saing) > 0) {
                        $insertDayaSaing = [];
                        foreach ($daya_saing as $item) {
                            $insertDayaSaing[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                // 'date' => $item['date'],
                                'date' => $date,
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertDayaSaing);
                    }
                    if (count($kepuasan_konsumen) > 0) {
                        $insertKepuasanKonsumen = [];
                        foreach ($kepuasan_konsumen as $item) {
                            $insertKepuasanKonsumen[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                // 'date' => $item['date'],
                                'date' => $date,
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertKepuasanKonsumen);
                    }
                    if (count($kapasitas_karyawan) > 0) {
                        $insertKapasitasKaryawan = [];
                        foreach ($kapasitas_karyawan as $item) {
                            $insertKapasitasKaryawan[] = [
                                'user_id' => $item['user_id'],
                                'dimensi_id' => $item['dimensi_id'],
                                'kpi_id' => $item['kpi_id'],
                                // 'date' => $item['date'],
                                'date' => $date,
                                'nilai' => $item['value'],
                                'max_nilai' => $item['max_nilai'],
                            ];
                        }
                        DB::table('pas_penilaian_others')->insert($insertKapasitasKaryawan);
                    }

                    $InsertToFinalRecord = DB::table('pas_final_record_3ps')->insertGetId([
                        'user_id' => $final_record['user_id'],
                        'id_3p' => $final_record['id_3p'],
                        // 'date' => $final_record['date'],
                        'date' => $date,
                        'nilai' => $final_record['nilai'],
                    ]);

                    if (!$InsertToFinalRecord) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Gagal masuk ke database [code: D-A-500]',
                        ], 500);
                    }

                    DB::commit();

                    return response()->json([
                        'statusFinal' => 'Data Final tersimpan',
                        'statusCode' => 200
                    ]);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Gagal masuk ke database [code: BR1-500] '+ $e->getMessage(),
                    'statusCode' => 500,
                ], 500);
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
}
