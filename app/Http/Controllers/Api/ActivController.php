<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = DB::table('activities')
                ->join('users', 'users.id', 'activities.user_id')
                ->select('activities.*', 'users.name as username')
                ->orderBy('time','DESC')
                ->get();
            return response()->json([
                'data' => $data,
                'message' => 'sukses ambil data',
                'statusCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan pengambilan data [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ], 500);
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
        try {
            $request->validate([
                'user_id' => 'required',
                'time' => 'required',
                'details' => 'required',
            ]);

            $insert = Activity::create([
                'user_id' => $request->user_id,
                'time' => $request->time,
                'details' => $request->details,
            ]);

            return response()->json([
                'data' => $insert,
                'message' => 'Berhasil menambah data',
                'statusCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan penyimpanan data [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ], 500);
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
            $data = Activity::findOrFail($id);

            return response()->json([
                'data' => $data,
                'message' => 'berhasil mendapatkan data',
                'statusCode' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ], 500);
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
        try {
            $request->validate([
                'user_id' => 'required',
                'time' => 'required',
                'details' => 'required'
            ]);

            DB::table('activities')->where('id',$id)->update([
                'user_id' => $request->user_id,
                'time' => $request->time,
                'details' => $request->details,
            ]);

            return response()->json([
                'message' => 'Berhasil mengupdate data',
                'statusCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan penyimpanan data [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ], 500);
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
        try {
            DB::table('activities')->where('id',$id)->delete();

            return response()->json([
                'message' => 'berhasil menghapus data',
                'statusCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan penyimpanan data [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ], 500);
        }
    }
}
