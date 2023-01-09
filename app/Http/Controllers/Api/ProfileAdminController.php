<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProfileAdminController extends Controller
{
    public $successStatus = 200; //jika data sukses di kirim maka akan ada response code 200
    public $errorStatus = 403;    //jika data gagal dikirim maka akan ada response code 403
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->user();
        try {
            $data = DB::table('users')->where('id','=',$userId->id)->first();
            return response()->json(
                [
                    'data' => $data
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    public function updateself(Request $request){
        $userId = auth()->user();
        // dd("masuk");
        try {
            $data = User::findOrfail($userId->id)->update([
                'nik' => $request->nik,
                'password' => bcrypt($request->password)
            ]);
            return response()->json(
                [
                    'data' => $data
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
