<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use DB;

class TestController extends Controller
{
    public $successStatus = 200;

    public function store(Request $request)
    {
        DB::beginTransaction();
        $qId=DB::table('test_questions')->insertGetId([
            'course_id' => $request->course_id,
            'description' => $request->description,
        ]);
        foreach ($request->answers as $answer) {
            DB::table('test_answers')->insert([
                'test_question_id' => $qId,
                'name' => $answer['name'],
                'is_true' => $answer['is_true'],
            ]);
        }
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function index(Request $request, $id)
    {
        $data = DB::table('test_questions')
        ->where('course_id', $id)
        ->orderBy('id')
        ->get();
        $array=array();
        foreach ($data as $key => $value) {
            $var = $value;
            $var->answers = DB::table('test_answers')->where('test_question_id', $value->id)->select('name','is_true')->get();
            array_push($array, $var);
        }
        return response()->json(['data' => $array]);
    }

}
