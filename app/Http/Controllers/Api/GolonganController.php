<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
        return response()->json(
            ['data' => Golongan::whereNotIn('name',['Staff','Ass. Manager','Ass. Supervisor'])->orderBy('code','ASC')->get()]
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

    public function get_golongan_by_company()
    {
        return response()->json([
            'data' => Golongan::query()
                ->whereHas('users', function (Builder $q){
                    $q->where('company_id', auth()->user()->company_id);
                })->get()
        ]);
    }
}
