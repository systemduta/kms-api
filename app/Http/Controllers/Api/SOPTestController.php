<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sop;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SOPTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Test controller apakah bisa terhubung dan bisa mengambil data
        // $cobaId = SOP::all();
        // dd($cobaId);

        // ambil data SOP 4 data dari SOP database dengan ID
        $id = Sop::orderBy('id', 'DESC')->limit(4)->get();
        return response()->json(['id' => $id]);

        // ambil data 4 user terbaru dari yang baru ditambahkan
        $user = User::orderBy('username', 'DESC')->limit(4)->get();
        return response()->json(['username' => $user]);

        // tampilkan data pada dashboard |clue dari file yang harus diedit dashbordanalytics.vue
        // pengembangannya bisa menampilkan data detil dari SOP ketika 4 tampilan data di dasboard sudah bisa tampil dengan model pop up

        
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
