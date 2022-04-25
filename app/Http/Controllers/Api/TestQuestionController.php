<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestQuestionController extends Controller
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
        $data = TestQuestion::where('id', $id)->orderBy('id')->get();
        // $array=array();
        foreach ($data as $key => $value) {
            $var = $value;
            $var->answers = DB::table('test_answers')->where('test_question_id', $value->id)->get();
            // array_push($array, $var);
        }
        return response()->json(['success' => $var], $this->successStatus);
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
        DB::table('test_questions')->where('id',$id)->update([
            'is_pre_test' => $request->is_pre_test,
            'description' => $request->description,
        ]);
        try{
            foreach ($request->answers as $key) {
                DB::table('test_answers')->where('id',$key['id'])->update([
                    'id'      => $key['id'],
                    'name'    => $key['name'],
                    'is_true' => $key['is_true'],
                ]);
            }
            return response()->json([
                'message' => 'Data berhasil di Update'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => $e
            ]);
        }
        // $comments = $request->answers;
        // //return $comments;
        // if (! empty($comments))
        // {
        //     // return $comments;
        //     foreach ($comments as $key) {
        //         DB::table('test_answers')->where('id',$key['id'])->update([
        //             'name' => $key['name'],
        //             'is_true' => $key['is_true'],
        //         ]);
        //     }
        //     return response()->json([
        //         'message' => 'Data berhasil di Update!',
        //     ], 201);
        // }
        // else
        // {
        //     return response()->json([
        //         'message' => 'Data gagal di Update!',
        //     ], 201);
        // }

        return response()->json([ 'message' => 'Data berhasil di Update!']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tq = DB::table('test_questions')->where('id',$id)->select('id')->get();
        DB::table('test_answers')->where('test_question_id',$tq[0]->id)->select('id')->delete();
        DB::table('test_questions')->where('id',$id)->select('id')->delete();
        return response()->json([
            'message' => 'delete successfully'
        ], $this->successStatus);

    }
}
