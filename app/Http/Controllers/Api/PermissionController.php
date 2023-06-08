<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        try {
            $user = User::with(['company', 'organization'])
                ->where('status', '!=', 0)
                ->orderBy('name', 'ASC')
                ->get();
            return response()->json(['data' => $user]);
            return response()->json([
                'data' => $user,
                'message' => 'sukses ambil data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
        }
    }

    public function index()
    {
        try {
            $datas = DB::table('permissions')
                ->join('users', 'permissions.user_id', '=', 'users.id')
                ->select('permissions.*', 'users.name as username')
                ->get();
            return response()->json([
                'data' => $datas,
                'message' => 'sukses ambil data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
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
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'statusCode' => 401], 401);
            }

            $cekData = DB::table('permissions')->where('user_id',$request->user_id)->count();
            if ($cekData) {
                return response()->json(['message' => 'User telah terdaftar', 'statusCode' => 401], 401);
            }

            $isSuperAdmin = $request->isSuperAdmin ?? 0;
            $isSOP = $request->isSOP ?? 0;
            $isKMS = $request->isKMS ?? 0;
            $is1VHS = $request->is1VHS ?? 0;
            $isPAS = $request->isPAS ?? 0;

            $insert = DB::table('permissions')->insertGetId([
                'user_id' => $request->user_id,
                'isSuperAdmin' => $isSuperAdmin,
                'isSOP' => $isSOP,
                'isKMS' => $isKMS,
                'is1VHS' => $is1VHS,
                'isPAS' => $isPAS,
            ]);

            return response()->json([
                'data' => $insert,
                'message' => 'sukses tambah data',
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $datas = DB::table('permissions')
                ->join('users', 'permissions.user_id', '=', 'users.id')
                ->where('permissions.id',$id)
                ->select('permissions.*', 'users.name as username')
                ->first();
            return response()->json([
                'data' => $datas,
                'message' => 'sukses ambil data'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'isSuperAdmin' => 'required',
                'isSOP' => 'required',
                'isKMS' => 'required',
                'is1VHS' => 'required',
                'isPAS' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'statusCode' => 401], 401);
            }

            $update = Permission::findOrfail($id)->update([
                'user_id' => $request->user_id,
                'isSuperAdmin' => $request->isSuperAdmin,
                'isSOP' => $request->isSOP,
                'isKMS' => $request->isKMS,
                'is1VHS' => $request->is1VHS,
                'isPAS' => $request->isPAS,
            ]);

            return response()->json([
                'data' => $update,
                'message' => 'sukses update data',
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Permission::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus',
                'statusCode' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 403,
            ], 403);
        }
    }
}
