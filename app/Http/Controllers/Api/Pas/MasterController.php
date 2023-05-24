<?php

namespace App\Http\Controllers\Api\Pas;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Organization;
use App\Models\Pas_3P;
use App\Models\User;
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
    

    public function all_ind(){
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
    public function all_kpi(){
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
    public function all_dimensi(){
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
    public function index(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'idCompany' => 'required',
                'idDivisi' => 'required',
                'idUser' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $dataCompany = Company::find($request->idCompany);
            $dataOrg = Organization::find($request->idDivisi);
            $dataUser = User::find($request->idUser);
            $datas = Pas_3P::all();

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

    public function index_3p()
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
