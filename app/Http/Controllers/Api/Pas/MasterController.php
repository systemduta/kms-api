<?php

namespace App\Http\Controllers\Api\Pas;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Pas_3P;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getKpi(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nameDimensi' => 'required',
                'id3p' => 'required',
                'idCompany' => 'required',
                'idDivisi' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                    'statusCode' => 401
                ], 401);
            }
            $getIdDimensi = DB::table('pas_dimensis')
                        ->where('name',$request->nameDimensi)
                        ->first();
            if ($request->id3p==1) {
                $getKpi = DB::table('pas_kpis')
                        ->where('3p_id',$request->id3p)
                        ->where('dimensi_id',$getIdDimensi->id)
                        ->get();
            }else{
                $getKpi = DB::table('pas_kpis')
                ->where('3p_id',$request->id3p)
                ->where('dimensi_id',$getIdDimensi->id)
                ->where('company_id',$request->idCompany)
                ->where('division_id',$request->idDivisi)
                ->get();
            }
            
            return response()->json([
                'datas'=>$getKpi,
                'message'=>'sukses',
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ],403);
        }
    }
    public function finalsave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'user_id' => 'required',
            'nilai' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 401
            ], 401);
        }

        $bulan = Carbon::parse($request->date)->format('m');
        $tahun = Carbon::parse($request->date)->format('Y');

        $cekData = DB::table('pas_final_skors')
            ->where('user_id', $request->user_id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->exists();

        if ($cekData) {
            return response()->json([
                'message' => "User sudah pernah dinilai pada bulan dan tahun yang dipilih",
            ], 403);
        }

        try {
            DB::beginTransaction();

            $saveGetId = DB::table('pas_final_skors')->insertGetId([
                'date' => $request->date,
                'user_id' => $request->user_id,
                'nilai' => $request->nilai,
            ]);

            if ($saveGetId) {
                DB::commit();

                return response()->json([
                    'data' => $saveGetId,
                    'statusCode' => 200,
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'message' => 'Gagal menyimpan data',
                    'statusCode' => 500,
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function all_ind()
    {
        try {
            $datas = DB::table('pas_ind_penilaians')->get();
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
    public function all_kpi()
    {
        try {
            $datas = DB::table('pas_kpis')->get();
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
    public function all_dimensi()
    {
        try {
            $datas = DB::table('pas_dimensis')->get();
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
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'idUser' => 'required',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $dataCompany = Company::find($request->idCompany);
            $dataOrg = Organization::find($request->idDivisi);
            $dataUser = User::find($request->idUser);
            $bulan = Carbon::parse($request->date)->format('m');
            $tahun = Carbon::parse($request->date)->format('Y');
            $datas = DB::table('pas_3p as p3')
                ->leftJoin('pas_final_record_3ps as pf', function ($join) use ($request, $bulan, $tahun) {
                    $join->on('pf.id_3p', '=', 'p3.id')
                        ->where('pf.user_id', $request->idUser)
                        ->whereMonth('pf.date', $bulan)
                        ->whereYear('pf.date', $tahun);
                })
                ->select('p3.*', 'pf.nilai')
                ->get();

            return response()->json(
                [
                    'data' => $datas,
                    'dataCompany' => $dataCompany,
                    'dataOrg' => $dataOrg,
                    'dataUser' => $dataUser,
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
    public function index_employee(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idCompany' => 'required',
                'idDivisi' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $Company = Company::find($request->idCompany);
            $Organization = Organization::find($request->idDivisi);
            $listEmployee = User::where('company_id', $request->idCompany)
                ->where('organization_id', $request->idDivisi)
                ->where('status', 1)
                ->get();
            return response()->json(
                [
                    'company' => $Company,
                    'divisi' => $Organization,
                    'data' => $listEmployee,
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
    public function index_division($id)
    {
        try {
            $datas = DB::table('organizations')
                ->where('company_id', $id)
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

    public function index_company()
    {
        try {
            $user = auth()->user();
            return response()->json(
                ['data' => Company::whereNotIn('name', [
                    'MAESA HOLDING',
                    'ANUGERAH UTAMA MOTOR',
                    'BANK ARTHAYA',
                    'CUN MOTOR GROUP',
                    'DUA TANGAN INDONESIA',
                    'ES KRISTAL PMP GROUP',
                    'HENNESY CUISINE',
                    'KOPERASI SDM',
                    'MAESA FOUNDATION',
                    'MAESA HOTEL',
                    'MIXTRA INTI TEKINDO',
                    'PABRIK ES PMP GROUP',
                    'PANDHU DISTRIBUTOR',
                    'PRAMA LOGISTIC',
                    'PT. PUTRA MAESA PERSADA',
                    'Panen Mutiara Pakis',
                    'HENNESSY CUISINE',
                    'WERKST MATERIAL HANDLING',
                    'PT. Prama Madya Parama'
                ])
                    ->when(($user && $user->role != 1), function ($q) use ($user) {
                        return $q->where('id', $user->company_id);
                    })
                    ->orderBy('id', 'ASC')->get()]
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

    public function index_3p(Request $request)
    {
        try {
            $datas = Pas_3P::all();
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
}
