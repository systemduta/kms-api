<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = auth()->user();
        $dt = DB::table('user_scores as us')
            ->leftJoin('users as u','u.id','us.user_id')
            ->when($auth->role!=1, function ($q) use ($auth) {
                return $q->where('u.company_id', $auth->company_id);
            })
            ->where('u.golongan_id', $request->golongan_id)
            ->groupByRaw('us.user_id,u.username,u.name')
            ->selectRaw('
                u.username,
                u.name,
                sum(us.score) as point
            ')->orderBy('point','desc')->get();

        return response()->json(['data' => $dt]);
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

    public function exam_result(Request $request){
        $user = auth()->user();
        $result = \Illuminate\Support\Facades\DB::table('user_scores as us')
            ->leftJoin('users as u','u.id','us.user_id')
            ->leftJoin('courses as c','c.id','us.course_id')
            ->selectRaw('
                us.id,
                u.name,
                c.title,
                score,
                status,
                is_pre_test
            ')
            ->when($user->role!=1, function ($q) use ($user) {
                return $q->where('u.company_id', $user->company_id);
            })
            ->when(($user->company_id==1 && $user->organization_id==11), function ($query) {
                return $query->where('c.type', '!=', 3);
            })
            ->when(($user->company_id==1 && $user->organization_id==20), function ($query) {
                return $query->where('c.type', '=', 3);
            })
            ->orderByDesc('us.id')
            ->get();
        return response()->json(['data' => $result]);
    }
}
