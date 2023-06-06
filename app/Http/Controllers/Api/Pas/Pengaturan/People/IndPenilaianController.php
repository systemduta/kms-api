<?php

namespace App\Http\Controllers\Api\Pas\Pengaturan\People;

use App\Http\Controllers\Controller;
use App\Models\Pas_ind_penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_per_kpi($id)
    {
        try {
            $datas = Pas_ind_penilaian::where('kpi_id', $id)->orderBy('nilai', 'desc')->orderBy('grade', 'asc')->get();

            return response()->json([
                'data' => $datas,
                'message' => 'sukses'
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
        }
    }

    public function index()
    {
        try {
            $datas = Pas_ind_penilaian::all();
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
                'id_3p' => 'required',
                'kpi_id' => 'required',
                'nilai' => 'required',
                'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $cek = DB::table('pas_ind_penilaians')
                ->where('3p_id', $request->id_3p)
                ->where('kpi_id', $request->kpi_id)
                ->where('nilai', $request->nilai)
                ->where('grade', $request->grade)
                ->count();
            if ($cek) {
                return response()->json(
                    [
                        'message' => 'Data Sama',
                    ],
                    403
                );
            } else {
                $InsertGetId = DB::table('pas_ind_penilaians')->insertGetId([
                    '3p_id' => $request->id_3p,
                    'kpi_id' => $request->kpi_id,
                    'nilai' => $request->nilai,
                    'grade' => $request->grade,
                    'desc' => $request->desc,
                ]);

                return response()->json(
                    [
                        'data' => $InsertGetId,
                        'message' => 'success',
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                403
            );
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
            $data = DB::table('pas_ind_penilaians')
                ->where('id', $id)
                ->first();
            return response()->json(
                [
                    'data' => $data,
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
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'id_3p' => 'required',
                'kpi_id' => 'required',
                'nilai' => 'required',
                'grade' => 'required',
                'desc' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $InsertGetId = DB::table('pas_ind_penilaians')->where('id', $request->id)->update([
                '3p_id' => $request->id_3p,
                'kpi_id' => $request->kpi_id,
                'nilai' => $request->nilai,
                'grade' => $request->grade,
                'desc' => $request->desc,
            ]);

            return response()->json(
                [
                    'data' => $InsertGetId,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Pas_ind_penilaian::findOrFail($id);
            $delete->delete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
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
