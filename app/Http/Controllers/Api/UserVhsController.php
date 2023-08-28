<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $successStatus = 200; //variabel ayng akan dipangggil saat operasi sukses dilakukan
    public $errorStatus = 403; //variabel yang akan di panggil saat operasi gagal dilakukan

    public function detailUser($id)
    {
        try {
            $users = DB::table('users')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->join('organizations', 'organizations.id', '=', 'users.organization_id')
                ->leftJoin('jadwal_user_vhs', 'jadwal_user_vhs.user_id', '=', 'users.id')
                ->leftJoin('jadwalvhs', 'jadwalvhs.id', '=', 'jadwal_user_vhs.jadwal_id')
                ->where('users.id', $id)
                ->select('users.id', 'users.nik', 'users.name', 'companies.name as com_name', 'organizations.name as org_name', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy', 'jadwalvhs.name as vhsname', 'jadwalvhs.batch as batchvhs')
                ->first();
            $vhs = DB::table('users')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->join('organizations', 'organizations.id', '=', 'users.organization_id')
                ->leftJoin('jadwal_user_vhs', 'jadwal_user_vhs.user_id', '=', 'users.id')
                ->leftJoin('jadwalvhs', 'jadwalvhs.id', '=', 'jadwal_user_vhs.jadwal_id')
                ->where('users.id', $id)
                ->select('jadwalvhs.*', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy')
                ->get();
            return response()->json(
                [
                    'user' => $users,
                    'vhs' => $vhs,
                    'message' => 'get successfully'
                ],
                $this->successStatus
            );
        } catch (\Exception $th) {
            return response()->json([
                'massage' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
        }
    }

    public function updateList(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id'       => 'required',
            'isBasic'       => 'required',
            'isClass'       => 'required',
            'isCamp'        => 'required',
            'isAcademy'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $update = DB::table('users')->where('id', $id)->update([
                'isBasic' => $request->isBasic,
                'isClass' => $request->isClass,
                'isCamp' => $request->isCamp,
                'isAcademy' => $request->isAcademy,
            ]);
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Update Riwayat User 1VHS'
            ]);
            return response()->json(
                [
                    'success' => $update,
                    'message' => 'insert successfully'
                ],
                $this->successStatus
            );
        } catch (\Exception $th) {
            return response()->json([
                'massage' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
        }
    }

    public function listUser($id)
    {
        try {
            $data = DB::table('users')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->join('organizations', 'organizations.id', '=', 'users.organization_id')
                ->where('users.company_id', $id)
                ->where('users.status', '!=', 0)
                ->groupBy('users.id', 'users.nik', 'users.name', 'companies.name', 'organizations.name', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy')
                ->select('users.id', 'users.nik', 'users.name', 'companies.name as com_name', 'organizations.name as org_name', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy')
                ->orderBy('users.name', 'asc')
                ->get();


            return response()->json([
                'message' => 'success',
                'data'  => $data,
            ], $this->successStatus);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
        }
    }

    public function index()
    {
        try {
            $data = DB::table('users')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->join('organizations', 'organizations.id', '=', 'users.organization_id')
                ->select('users.id', 'users.nik', 'users.name', 'companies.name as com_name', 'organizations.name as org_name', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy')
                ->get();

            return response()->json([
                'message' => 'success',
                'data'  => $data,
            ], $this->successStatus);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
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
        try {
            $data = DB::table('users')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->join('organizations', 'organizations.id', '=', 'users.organization_id')
                ->where('users.id', $id)
                ->select('users.id', 'users.nik', 'users.name', 'companies.name as com_name', 'organizations.name as org_name', 'users.isBasic', 'users.isClass', 'users.isCamp', 'users.isAcademy')
                ->get();

            return response()->json([
                'message' => 'success',
                'data'  => $data,
            ], $this->successStatus);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
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
            'user_id'       => 'required',
            'user_name'     => 'required',
            'type'          => 'required',
            'jadwal_id'     => 'required',
            'isBasic'       => 'required',
            'isClass'       => 'required',
            'isCamp'        => 'required',
            'isAcademy'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $update = DB::table('users')->where('id', $request->user_id)->update([
                'isBasic' => $request->isBasic,
                'isClass' => $request->isClass,
                'isCamp' => $request->isCamp,
                'isAcademy' => $request->isAcademy,
            ]);
            return response()->json(
                [
                    'success' => $update,
                    'message' => 'insert successfully'
                ],
                $this->successStatus
            );
        } catch (\Exception $th) {
            return response()->json([
                'massage' => 'error',
                'data' => $th->getMessage(),
            ], $this->errorStatus);
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
