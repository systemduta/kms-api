<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\UserScoreVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserScoreVhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        try {
            // $data=UserScoreVhs::join('users','user_score_vhs.user_id','=','users.id')
            //     ->join('companies','companies.id','=','users.company_id')
            //     ->join('materi_vhs','materi_vhs.id','=','user_score_vhs.materi_id')
            //     ->join('question_vhs','question_vhs.materi_id','=','materi_vhs.id')
            //     ->join('answer_vhs','answer_vhs.user_id','=','users.id')
            //     ->select('user_score_vhs.id','users.name as nama_user','users.username as username','companies.name as nama_company','materi_vhs.name as nama_materi','question_vhs.question as question','user_score_vhs.score','answer_vhs.answer','materi_vhs.type')
            //     ->get();
            $data = DB::table('users')
                    ->join('user_score_vhs','user_score_vhs.user_id','users.id')
                    ->join('companies','companies.id','users.company_id')
                    ->join('materi_vhs','materi_vhs.id','user_score_vhs.materi_id')
                    ->join('question_vhs','question_vhs.materi_id','materi_vhs.id')
                    ->join('answer_vhs','answer_vhs.question_id','question_vhs.id')
                    ->select('user_score_vhs.id',
                    'users.name as nama_user',
                    'users.username as username',
                    'materi_vhs.name as namamateri',
                    'question_vhs.question as question',
                    'answer_vhs.answer as answer',
                    'materi_vhs.type',
                    'user_score_vhs.score')
                    ->get();
            return response()->json([
                'success'=>$data,
                'message'=>'get successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }   
    }

    public function getUserPerCompany($id) {
        try {

            $data=DB::table('users')
                    ->join('companies','companies.id','=','users.company_id')
                    ->join('user_score_vhs','user_score_vhs.user_id','=','users.id')
                    ->join('materi_vhs','materi_vhs.id','=','user_Score_vhs.materi_id')
                    ->join('question_vhs','question_vhs.materi_id','=','materi_vhs.id')
                    ->join('answer_vhs','answer_vhs.question_id','=','question_vhs.id')
                    ->select('users.name as nama_user','users.username as username','companies.name as nama_company','materi_vhs.name as nama_materi','question_vhs.question as question','user_score_vhs.score','answer_vhs.answer','materi_vhs.type')
                    ->where('users.company_id',$id)
                    ->get();
            $company = Company::where('id',$id)->get();
            return response()->json([
                   'company'=>$company,
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
        $validator = Validator::make($request->all(),[
            'materi_id'             => 'required',
            'user_id'               => 'required',
            'score'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        $cekData = UserScoreVhs::where('user_id',$request->user_id)->where('materi_id',$request->materi_id)->count();
        if ($cekData==0) {
            try {
                DB::beginTransaction();
                $data = DB::table('user_score_vhs')->insertGetId([
                    'materi_id'         => $request->materi_id,
                    'user_id'           => $request->user_id,
                    'score'             => $request->score,
                    'status'             => "1",                    
                ]);
                DB::commit();
    
                return response()->json([
                    'success'=>$data,
                    'message'=>'get successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } 
        } else {
            return response()->json(['error' => 'user sudah dinilai'], 500);
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
            $data=DB::table('user_score_vhs')
                ->join('users','user_score_vhs.user_id','=','users.id')
                ->join('companies','companies.id','=','users.company_id')
                ->join('materi_vhs','materi_vhs.id','=','user_score_vhs.materi_id')
                ->join('question_vhs','question_vhs.materi_id','=','materi_vhs.id')
                ->join('answer_vhs','answer_vhs.user_id','=','users.id')
                ->select('user_score_vhs.id as user_score_vhs_id','users.id as users_id','materi_vhs.id as id_materi_vhs','users.name as nama_user','users.username as username','companies.name as nama_company','materi_vhs.name as nama_materi','question_vhs.question as question','user_score_vhs.score','answer_vhs.answer','materi_vhs.type')
                ->where('user_score_vhs.id',$id)
                ->get();
            return response()->json([
                'success'=>$data,
                'message'=>'get successfully']);
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
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'materi_id'             => 'required',
            'user_id'               => 'required',
            'score'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

            try {
                $data = UserScoreVhs::findOrfail($id)->update([
                    'materi_id'         => $request->materi_id,
                    'user_id'           => $request->user_id,
                    'score'             => $request->score,
                ]);
    
                return response()->json([
                    'success'=>$data,
                    'message'=>'get successfully']);
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
            UserScoreVhs::destroy($id);
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }
}
