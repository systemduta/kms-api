<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada function index, terdapat sebuah query yang menggunakan fungsi whereNotIn() untuk memfilter data dari model Golongan yang tidak memiliki nama sesuai dengan daftar nama yang disebutkan di dalam [ ]. Selanjutnya, fungsi orderBy() digunakan untuk mengurutkan data berdasarkan kolom 'code' dengan urutan ascending ('ASC'). Kemudian, fungsi get() digunakan untuk mengeksekusi query tersebut. Setelah itu, hasil query tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json().
     */
    public function index()
    { 
        try {
            return response()->json(
                ['data' => Golongan::whereNotIn('name', ['Staff', 'Ass. Manager', 'Ass. Supervisor'])->orderBy('code', 'ASC')->get()]
            );
        } catch (\Exception $e) {
            return response()->json([
                'message'       => $e->getMessage(),
                'statusCode'    => 500,
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
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'numeric', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                    'statusCode' => 404,
                ], 404);
            }

            $insert = DB::table('golongans')->insert([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json([
                'message'       => 'sukses insert data',
                'data'          => $insert,
                'statusCode'    => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'       => $e->getMessage(),
                'statusCode'    => 500,
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
            $data = Golongan::where('id', $id)->first();
            return response()->json([
                'data' => $data,
                'message' => 'sukses get data',
                'statusCode' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message'       => $e->getMessage(),
                'statusCode'    => 500,
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
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'numeric', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                    'statusCode' => 404,
                ], 404);
            }

            $golongan = DB::table('golongans')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'code' => $request->code,
                ]);

            // Fetch the updated data after the update query
            $updatedData = DB::table('golongans')->where('id', $id)->first();

            return response()->json([
                'data' => $updatedData, // Return the updated data instead of $golongan
                'message' => 'sukses update data',
                'statusCode' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
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
            $data = Golongan::findOrFail($id);
            if ($data) {
                Golongan::destroy($id);

                return response()->json([
                    'message' => 'Success destroy data'
                ]);
            } else {
                return response()->json(
                    [
                        'message' => 'cannot find id'
                    ],403
                );
            }    
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'statusCode' => 500,
            ], 500);
        }
    }

    public function get_golongan_by_company()
    {
        // return response()->json([
        //     'data' => Golongan::query()
        //         ->whereHas('users', function (Builder $q) {
        //             $q->where('company_id', auth()->user()->company_id);
        //         })->get()
        // ]);
        try {
            return response()->json(
                ['data' => Golongan::whereNotIn('name', ['Staff', 'Ass. Manager', 'Ass. Supervisor'])->orderBy('code', 'ASC')->get()]
            );
        } catch (\Exception $e) {
            return response()->json([
                'message'       => $e->getMessage(),
                'statusCode'    => 500,
            ], 500);
        }
    }
}
