<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnswerVhs;
use App\Models\QuestionVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=DB::table('answer_vhs')
                ->join('materi_vhs','materi_vhs.id','answer_vhs.materi_id')
                ->join('question_vhs','question_vhs.id','answer_vhs.question_id')
                ->join('users','users.id','answer_vhs.user_id')
                ->select('materi_vhs.name as nama_materi','question_vhs.question as name_question','users.name','answer_vhs.answer','answer_vhs.created_at as date_update')
                ->get();
         return response()->json([
            'success'=>$data,
            'message'=>'get successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    public function getAnswer($id){
        try {
            $data=DB::table('answer_vhs')
                ->join('materi_vhs','materi_vhs.id','answer_vhs.materi_id')
                ->join('question_vhs','question_vhs.id','answer_vhs.question_id')
                ->join('users','users.id','answer_vhs.user_id')
                ->select(
                    'answer_vhs.id as id_answer',
                    'materi_vhs.id as id_materi',
                    'users.id as id_user',
                    'answer_vhs.question_id as id_question',
                    'materi_vhs.name as nama_materi',
                    'question_vhs.question as name_question',
                    'users.name',
                    'answer_vhs.answer',
                    'answer_vhs.created_at as date_update',)
                ->where('answer_vhs.question_id',$id)
                ->get();

            $data3 = QuestionVhs::where('question_vhs.id',$id)->first();
            // dd($data3->question);
         return response()->json([
            'question'=>$data3,
            'success'=>$data,
            'message'=>'get successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    public function getSingleAnswer($id){
        try {
            $data=DB::table('answer_vhs')
                ->join('materi_vhs','materi_vhs.id','answer_vhs.materi_id')
                ->join('question_vhs','question_vhs.id','answer_vhs.question_id')
                ->join('users','users.id','answer_vhs.user_id')
                ->select(
                    'answer_vhs.id as id_answer',
                    'materi_vhs.id as id_materi',
                    'users.id as id_user',
                    'answer_vhs.question_id as id_question',
                    'materi_vhs.name as nama_materi',
                    'question_vhs.question as name_question',
                    'users.name',
                    'answer_vhs.answer',
                    'answer_vhs.created_at as date_update')
                ->where('answer_vhs.id',$id)
                ->first();
         return response()->json([
            'success'=>$data,
            'message'=>'get successfully']);
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
