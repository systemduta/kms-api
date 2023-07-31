<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * hubungan route: 
     *      Route::get('get_company', 'CompanyController@ind
     * fungsi : 
     *      untuk memperoleh data perusahaan yang ada saat ini. fungsi whereNotIn digunakan untuk pengecualian nama perusahaan agar tidak tampil di dalam response nantinya.
     */
    public function index()
    { 
        $user = auth()->user();
        // dd($user);
        // $company = Company::query()
        //     ->when(($user && $user->role!=1), function ($q) use ($user) {
        //         return $q->where('id', $user->company_id);
        //     })->get();
        // return response()->json(['data' => 'masuk'],403);
        return response()->json(
            ['data' => Company::
            whereNotIn('name',[
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
                ->orderBy('id','ASC')->get()]
        );

        // return response()->json(
        //     ['data' => Company::orderBy('id','DESC')->get()]
        // );
    }

    public function getCompany($id)
    {
        $detailCompany =  Company::whereNotIn('name',[
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
            ])->where('id',$id)->orderBy('id','ASC')->get();
        $listDivision = DB::table('companies')
            ->join('organizations','organizations.company_id','=','companies.id')
            ->where('companies.id',$id)
            ->get();
        return response()->json(
            [
                'detailcompany' =>$detailCompany,
                'listorganizations' =>$listDivision,
            ]
        );
    }

    public function getDetail(Request $request)
    {
        try {
            $datas=DB::table('users')
                ->join('organizations','organizations.id','=','users.organization_id')
                ->join('companies','companies.id','=','users.company_id')
                ->where('organizations.id',$request->iddivision)
                ->where('companies.id',$request->idcompany)
                ->select('users.id','users.nik','users.name','users.status','users.email')
                ->get();
            return response()->json(
                    [
                        'data' =>$datas,
                        'message' =>'success',
                    ]
                );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        try {
            $data= Company::create([
                'code'   => $request->code,
                'name'   => $request->name,
            ]);
            return response()->json(
                [
                    'data'      => $data,
                    'message'   => "saved successfully",
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
            $data = Company::find($id);
            return response()->json(
                [
                    'data'      => $data,
                    'message'   => "saved successfully",
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        try {
            $data= Company::findOrFail($id)->update([
                'code'   => $request->code,
                'name'   => $request->name,
            ]);
            return response()->json(
                [
                    'data'      => $data,
                    'message'   => "saved successfully",
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => $th->getMessage(),
            ]);
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
        //
    }
}
