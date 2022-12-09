<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public $successStatus   =   200;
    public $errorStatus     =   403;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uji() {

    }
    public function index()
    {
        $user = auth()->user();
        $users = DB::table('users')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('company_id', $user->company_id);
                })
            ->count();
        $sop = DB::table('sops')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('company_id', $user->company_id);
                })
            ->count();
        $courses = DB::table('courses')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
            return $q->where('company_id', $user->company_id);
            })
            ->count();
        $vhs = DB::table('jadwalvhs')->count();
        $lastUser = DB::table('users')
            ->join('companies','companies.id','=','users.company_id')
            ->when(($user && $user->role!=1), function ($q) use ($user) {
            return $q->where('company_id', $user->company_id);
            })->skip(0)->take(4)->orderBy('id','DESC')->select('users.*','companies.name as name_company')->get();
        return response()->json(
            [
                'code'      =>'200',
                'users'     =>$users,
                'sop'       =>$sop,
                'courses'   =>$courses,
                'vhs'       =>$vhs,
                'lastuser'  =>$lastUser,
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
