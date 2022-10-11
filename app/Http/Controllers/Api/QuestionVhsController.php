<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriVhs;
use App\Models\QuestionVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=QuestionVhs::join('materi_vhs',function($join){
                $join->on('question_vhs.materi_id','=','materi_vhs.id');
            })->select('question_vhs.id as id_question','materi_vhs.id','question_vhs.*','materi_vhs.*')->orderBy('question_vhs.id','desc')->get();
            return response()->json(
                [
                    'data' => $data
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    public function listMateriVhs(){
        try {
            $data=MateriVhs::all();
            return response()->json(
                [
                    'data' => $data
                ]
            );
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'materi_id' => 'required',
            'question' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        try {
            $data= QuestionVhs::create([
                'materi_id' => $request->materi_id,
                'question' => $request->question,
            ]);
            return response()->json(
                [
                    'data' => "saved successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            $data=QuestionVhs::join('materi_vhs',function($join){
                $join->on('question_vhs.materi_id','=','materi_vhs.id');
            })
            ->select('question_vhs.id as id_question','materi_vhs.id','question_vhs.*','materi_vhs.*')
            ->where('question_vhs.id',$id)
            ->first();
            return response()->json(
                [
                    'data' => $data
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            'materi_id' => 'required',
            'question' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        try {
            $data= QuestionVhs::findOrfail($id)->update([
                'materi_id' => $request->materi_id,
                'question' => $request->question,
            ]);
            return response()->json(
                [
                    'data' => "saved successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            $delete = QuestionVhs::findOrFail($id);
            $delete->delete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
