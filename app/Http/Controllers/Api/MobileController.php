<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UserScore;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;

class MobileController extends Controller
{

    public $successStatus = 200;

    public function login_mobile(Request $request){
        // return response()->json($request,500);
        if(Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])){
            $user = Auth::user();
            $org=DB::table('organizations')->where('id', $user->organization_id)->first();
            if($request->isWeb=="1") {
                if($org->is_str!=1) return response()->json(['message' => 'Unauthorized'], 401);
            }
            $success['company_id'] = $user->company_id;
            $success['file'] = $user->file;
            $success['accessToken'] = $user->createToken('nApp')->accessToken;

            DB::table('users')->where('id', $user->id)->update([
                'token' => $request->token
            ]);

            return response()->json($success, $this->successStatus);
        }
        else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
//    Sementara ndak dipakai
//    public function course_list(Request $request)
//    {
//        $dt = DB::table('courses as c');
//        $dt = $dt->leftJoin('user_scores as us','us.course_id','c.id');
//        $dt = $dt->where('c.organization_id', auth()->user()->organization_id);
//        $dt = $dt->where('c.type', $request->type);
//        $dt = $dt->where('us.id','!=', null);
//        $dt = $dt->selectRaw('
//            c.id,
//            c.title,
//            c.description,
//            c.image,
//            if(us.id is not null, 1, 0) as is_going
//        ')->get();
//        $data=array();
//        return response()->json(['data' => $dt]);
//    }
    public function course_list_dashboard(Request $request)
    {
        $user = auth()->user();
        $dt = DB::table('courses as c');
        $dt = $dt->leftJoin('user_scores as us','us.course_id','c.id');
        $dt = $dt->when($request->type, function ($query) use ($request){
            return $query->where('c.type', $request->type);
        });
        $dt = $dt->when($request->type==1, function ($query){
            return $query->where('c.organization_id', auth()->user()->organization_id);
        });
        // $dt = $dt->whereRaw("(us.id is null or IF(us.user_id = $user->id,c.id))")
        // $dt = $dt->where(function($q){
        //     $q->where('us.id', null);

        //     $q->orWhere('us.user_id', '!=', auth()->user()->id);
        // });
        $dt = $dt->groupBy('c.id','c.title','c.description','c.image');
        $dt = $dt->orderBy('c.id','DESC');
        $dt = $dt->selectRaw('
            c.id,
            c.title,
            c.description,
            c.image,
            count(us.id) as jml,
            group_concat(us.user_id) as user_list
        ')->get();

        $data = array();
        foreach($dt as $value){
            $exclude = explode(",",$value->user_list);
            if(in_array($user->id,$exclude)) continue;
            array_push($data,$value);
        }

        return response()->json(['data' => $data]);
    }

    public function list_vhs()
    {
        $type = 3;
        $user = auth()->user();
        $dt = DB::table('courses as c');
        $dt = $dt->leftJoin('user_scores as us','us.course_id','c.id');
        $dt = $dt->where('c.type', $type);
        $dt = $dt->groupBy('c.id','c.title','c.description','c.image','c.video');
        $dt = $dt->orderBy('c.id','DESC');
        $dt = $dt->selectRaw('
            c.id,
            c.title,
            c.description,
            c.image as thumbnail,
            c.video,
            count(us.id) as jml,
            group_concat(us.user_id) as user_list
        ')->get();

        $data = array();
        foreach($dt as $value){
            $exclude = explode(",",$value->user_list);
            if(in_array($user->id,$exclude)) continue;
            array_push($data,$value);
        }

        return response()->json(['data' => $data]);
    }

    public function covert_question ($value) {
        return $value->whenNotEmpty(function ($pre) {
            $temp = collect([]);
            $pre->each(function ($item) use (&$temp) {
                $nameA ='';
                $nameB ='';
                $nameC ='';
                $nameD ='';
                $answer = '';
                $item->test_answers->each(function ($it, $key) use (&$nameA, &$nameB, &$nameC, &$nameD, &$answer) {
                    if ($key == 0) $nameA = $it->name;
                    if ($key == 1) $nameB = $it->name;
                    if ($key == 2) $nameC = $it->name;
                    if ($key == 3) $nameD = $it->name;
                    if ($it->is_true == 1) $answer = $it->name;
                });

                $temp->push([
                    'question' => $item->description,
                    'nameA' => $nameA,
                    'nameB' => $nameB,
                    'nameC' => $nameC,
                    'nameD' => $nameD,
                    'answer_true' => $answer,
                ]);
            });
            return $temp;
        });
    }

    public function course_detail($id)
    {
        $ori_data = Course::query()
            ->with(['pre_test_questions.test_answers'])
            ->with(['post_test_questions.test_answers'])
            ->findOrFail($id);

        $pre_test = $this->covert_question($ori_data->pre_test_questions);
        $post_test = $this->covert_question($ori_data->post_test_questions);

        $result = collect([
            'id' => $ori_data->id,
            'title' => $ori_data->title,
            'organization_id' => $ori_data->organization_id,
            'description' => $ori_data->description,
            'image' => $ori_data->image,
            'file' => $ori_data->file,
            'video' => $ori_data->video,
            'link' => $ori_data->link,
            'type' => $ori_data->type,
            'created_at' => $ori_data->created_at,
            'updated_at' => $ori_data->updated_at,
            'pre_test' => $pre_test,
            'test' => $post_test,
        ]);


//        $dt = DB::table('courses')->where('id', $id)->first();
//        $question = DB::table('test_questions')
//            ->where('course_id', $dt->id)
//            ->selectRaw('id, description')
//            ->get();
//        $arr_question = array();
//        $question->map(function($value) use (&$arr_question){
//            $answer = DB::table('test_answers')
//                ->where('test_question_id', $value->id)
//                ->selectRaw('id,name,is_true')
//                ->get();
//            $q_array = [
//                'question' => $value->description
//            ];
//            $letter = "A";
//            for($i=0;$i<count($answer);$i++) {
//                $dt = $answer[$i];
//                $q_array = array_merge($q_array,[
//                    'name'.$letter => $dt->name
//                ]);
//                $letter++;
//                if ($dt->is_true) {
//                    $q_array = array_merge($q_array,[
//                        'answer_true' => $dt->name,
//                    ]);
//                }
//            }
//            array_push($arr_question,$q_array);
//        });
//        $data = (Array) $dt;
//        $data['test'] = $arr_question;
        return response()->json(['data' => $result]);
    }

    public function user_course(Request $request)
    {
        $dt = DB::table('user_scores as us');
        $dt = $dt->leftJoin('courses as c','c.id','us.course_id');
        $dt = $dt->where('us.user_id', auth()->id());
        $dt = $dt->orderBy('us.id','DESC');
        $dt = $dt->selectRaw('
            us.id,
            us.course_id,
            us.score,
            us.status,
            c.image,
            c.title,
            c.description
        ')->get();

//        start of formating response
        $data= collect();
        $temp_id = null;
        $temp_course_id = null;
        $temp_score = null;
        $temp_status = null;
        $dt->each(function ($item) use (&$temp_id, &$temp_course_id, &$data, &$temp_score, &$temp_status){
            if (($item->id == $temp_id-1) && ($item->course_id == $temp_course_id)) {
                $modifiedElement = array_merge($data[($data->count())-1], [
                    "score" => $temp_score + $item->score,
                    "pre_score" => $item->score,
                    "post_score" => $temp_score,
                    "status" => ($item->status==2 && $temp_status==2) ? "2" : "1",
                    "pre_status" => $item->status,
                    "post_status" => $temp_status,
                ]);
                $data->put((($data->count())-1), $modifiedElement);
            } else {
                $data->push([
//                    "id" => $item->id,
                    "id" => $item->course_id,
                    "course_id" => $item->course_id,
                    "image" => $item->image,
                    "title" => $item->title,
                    "description" => $item->description,
                    "score" => $item->score,
                    "status" => $item->status,
                    "pre_score" => null,
                    "post_score" => null,
                    "pre_status" => null,
                    "post_status" => null,
                ]);
            }
            $temp_id = $item->id;
            $temp_course_id = $item->course_id;
            $temp_score = $item->score;
            $temp_status = $item->status;
        });
//        end of formating response

        return response()->json(['data' => $data]);
    }


    public function accept_course(Request $request)
    {
        $course_id = $request->course_id;
        $user_score = UserScore::query()
            ->where('course_id', $course_id)
            ->where('user_id', auth()->id())
            ->first();
        if ($user_score)
            return response()->json(['message' => 'you have taken this course'], 401);

        $course = Course::query()->findOrFail($course_id);
        $is_corp_value = $course->type == 3;

        UserScore::query()->when($is_corp_value, function ($query) use ($course_id){
            return $query->insert([
                [
                    'course_id' => $course_id,
                    'user_id' => auth()->id(),
                    'score' => 0,
                    'status' => 1,
                    'is_pre_test' => 1
                ],
                [
                    'course_id' => $course_id,
                    'user_id' => auth()->id(),
                    'score' => 0,
                    'status' => 1,
                    'is_pre_test' => 0
                ]
            ]);
        }, function ($query) use ($course_id) {
            return $query->insert([
                'course_id' => $course_id,
                'user_id' => auth()->id(),
                'score' => 0,
                'status' => 1,
            ]);
        });

        return response()->json(['message' => 'OK']);
    }

    public function submit_question(Request $request)
    {
        DB::beginTransaction();
        $user=auth()->user();
        // $score=0;
        // foreach ($request->answer as $key => $value) {
        //     $answer=DB::table('test_answers')->where('id', $value['question_answer_id'])->first();
        //     if ($answer->is_true==1) {
        //         $score+=20;
        //     }
        // }
        DB::table('user_scores')->updateOrInsert([
            'course_id' => $request->course_id,
            'user_id' => $user->id,
        ],[
            'score' => $request->score,
            'status' => 2
        ]);
//        DB::table('leaderboards')->updateOrInsert([
//            'user_id' => $user->id,
//        ],[
//            'point' => DB::raw('point+'.$request->score)
//        ]);
//        $cek = DB::table('leaderboards')->where('user_id', $user->id)->first();
//        $score = ceil($cek->point/100);
//        DB::table('leaderboards')->where('user_id', $user->id)->update([
//            'level' => $score
//        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function submit_answer(Request $request)
    {
        DB::beginTransaction();
        $user=auth()->user();
        $params = array();
        if ($request->filled('pre_test') && $request->pre_test) {
            $params = [
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'is_pre_test' => 1
            ];
        } else {
            $params = [
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'is_pre_test' => 0
            ];
        }
        DB::table('user_scores')->updateOrInsert($params,[
            'score' => $request->score,
            'status' => 2
        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function leaderboards()
    {
        $dt = DB::table('user_scores as us');
        $dt = $dt->leftJoin('users as u','u.id','us.user_id');
        $dt = $dt->where('u.golongan_id', auth()->user()->golongan_id);
        $dt = $dt->groupByRaw('us.user_id,u.name,u.image');
        $dt = $dt->selectRaw('
            u.name,
            u.image,
            sum(us.score) as total_score
        ');
        $dt = $dt->orderBy('total_score','desc')->get();
//        add ui avatar
        $data = collect([]);
        foreach ($dt as $d) {
            $data->push([
                'name' => $d->name,
                'image' => $d->image,
                'ui_avatar' => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.$d->name,
                'total_score' => $d->total_score,
            ]);
        }
        return response()->json(['data' => $data]);
    }

    public function post_calendar(Request $request)
    {
        DB::beginTransaction();
        DB::table('calendars')->insert([
            'user_id' => auth()->id(),
            'date' => Carbon::parse($request->date),
            'description' => $request->description
        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function get_calendar()
    {
        $dt=DB::table('calendars')->where('user_id', auth()->id())->selectRaw('date, description')->get();
        return response()->json(['data' => $dt]);
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json(['message' => 'Logout Success']);
    }

    public function details()
    {
        $user = User::with('company','organization')
            ->where('id', Auth::user()->id)
            ->get();
        return response()->json([
            'message' => 'success',
            'data' => $user
        ], $this->successStatus);
    }

    public function change_password(Request $request) {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }
}
