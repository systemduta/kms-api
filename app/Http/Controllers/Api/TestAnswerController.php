<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestAnswerController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $answer = DB::table('test_answers')->where('id',$id)->first();
        // dd($answer);
        return response()->json(['success' => $answer], $this->successStatus);
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
        TestAnswer::where('id',$id)->update([
            'name'      => $request->name,
            'is_true'   => $request->is_true,
        ]);

        return response()->json([
            'message'=>'update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::table('test_answers')->where('id',$id)->delete();
            return response()->json([
                'message' => 'delete successfully'
            ]);
        // try{
        //     DB::table('test_answers')->where('id',$id)->delete();
        //     return response()->json([
        //         'message' => 'delete successfully'
        //     ]);
        // }catch(Exception $e){
        //     return response()->json([
        //         'message' => $e
        //     ]);
        // }
    }
}
