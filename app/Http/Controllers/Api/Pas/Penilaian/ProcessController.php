<?php
//URUNG bagian ini besok
namespace App\Http\Controllers\Api\Pas\Penilaian;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProcessController extends Controller
{
    public function process(Request $request)
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
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'Routine' => 'required',
                'Cross Function' => 'required',
                'Interaction' => 'required',
                'final_record' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }
            $data = $request->only(['Routine', 'Cross Function', 'Interaction', 'final_record']);
            $routine = $data['Routine'];
            $cross_function = $data['Cross Function'];
            $interaction = $data['Interaction'];
            $final_record = $data['final_record'];

            try {
                DB::beginTransaction();
                $carbonDate = Carbon::createFromFormat('Y-m',$final_record['date']);
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
                    $carbonDate->day=1;
                    $date = $carbonDate->format('Y-m-d');
                    $insertRoutine = [];
                    foreach ($routine as $item) {
                        $insertRoutine[] = [
                            'user_id' => $item['user_id'],
                            'dimensi_id' => $item['dimensi_id'],
                            'kpi_id' => $item['kpi_id'],
                            // 'date' => $item['date'],
                            'date' => $date,
                            'nilai' => $item['value'],
                            'max_nilai' => $item['max_nilai'],
                        ];
                    }
                    DB::table('pas_penilaian_others')->insert($insertRoutine);

                    $insertCrossFunction = [];
                    foreach ($cross_function as $item) {
                        $insertCrossFunction[] = [
                            'user_id' => $item['user_id'],
                            'dimensi_id' => $item['dimensi_id'],
                            'kpi_id' => $item['kpi_id'],
                            // 'date' => $item['date'],
                            'date' => $date,
                            'nilai' => $item['value'],
                            'max_nilai' => $item['max_nilai'],
                        ];
                    }
                    DB::table('pas_penilaian_others')->insert($insertCrossFunction);
                    
                    $insertInteraction = [];
                    foreach ($interaction as $item) {
                        $insertInteraction[] = [
                            'user_id' => $item['user_id'],
                            'dimensi_id' => $item['dimensi_id'],
                            'kpi_id' => $item['kpi_id'],
                            // 'date' => $item['date'],
                            'date' => $date,
                            'nilai' => $item['value'],
                            'max_nilai' => $item['max_nilai'],
                        ];
                    }
                    DB::table('pas_penilaian_others')->insert($insertInteraction);

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
                        'statusRoutine' => 'Data Routine tersimpan',
                        'statusCrossFunction' => 'Data CrossFunction tersimpan',
                        'statusInteraction' => 'Data Interaction tersimpan',
                        'statusFinal' => 'Data Final tersimpan',
                        'statusCode' => 200
                    ]);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Gagal masuk ke database [code: BR1-500]' + $e->getMessage(),
                    'statusCode' =>500,
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
