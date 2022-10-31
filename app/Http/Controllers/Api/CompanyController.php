<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = auth()->user();
        // $company = Company::query()
        //     ->when(($user && $user->role!=1), function ($q) use ($user) {
        //         return $q->where('id', $user->company_id);
        //     })->get();
        // return response()->json(['data' => $company]);

        return response()->json(
            ['data' => Company::whereNotIn('name',[
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
                'WERKST MATERIAL HANDLING'
                ])->orderBy('id','ASC')->get()]
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
            'WERKST MATERIAL HANDLING'
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
