<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\UserScore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CourseController extends Controller
{
    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function coursedown($id)
    {
        $course = DB::table('courses')->select('file')->where('id',$id)->first();
        // $sprt = explode('/',$course->file);
        return response()->json(['data' => $course->file]);

        // return Storage::download('files/files',$sprt[1]);
    }
     
    public function index(Request $request)
    {
        $auth = auth()->user();
        $data = Course::query()
            ->when($auth->role!=1, function ($q) use ($auth) {
                return $q->where('company_id', $auth->company_id);
            })
            ->when(($request->filled('type') && $request->type == 3), function (Builder $query) use ($request){
                return $query->where('type', '=', 3);
            }, function (Builder $builder) use ($request) {
                return $builder->where('type','!=',3)
                    ->where('type', '!=' , 1)
                    ->orWhere(function (Builder $query) use ($request) {
                        return $query->where('type',1)
                            ->where('organization_id',$request->organization_id);
                    });
            })
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json(['data' => $data]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'numeric|nullable',
            'golongan_id' => 'numeric|nullable',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'video' => 'file|nullable',
            'file' => 'required',
            'link' => 'string|nullable',
            'type' => 'required',
            'questions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $auth = auth()->user();
        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'file_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        /* PREPARE VIDEO UPLOAD */
        $video_name = null;
        $video_path = null;
        $video = null;
        if ($request->hasFile('video')) {
            $video = $request->video;
            $video_path = 'files/course/video/';
            $video_name = Str::random(20).'.'.$video->getClientOriginalExtension();
        }
        /* END PREPARE VIDEO UPLOAD */
        try {
            DB::beginTransaction();
            $courseGetId=DB::table('courses')->insertGetId([
                'company_id' => $auth->company_id,
                'organization_id' => $request->organization_id ?? null,
                'golongan_id' => $request->golongan_id ?? null,
                'title' => $request->title,
                'description' => $request->description,
                'image' => '',
                'video' => $video_path.$video_name,
                'file' => 'files/'.$filename,
                'link' => $request->link,
                'type' => $request->type
            ]);

            /* START VIDEO UPLOAD */
            if ($request->hasFile('video')) {
                try {
                    Storage::disk('public')->put($video_path.$video_name, file_get_contents($video));
                } catch (Exception $e){
                    return response()->json(['error'=>$e->getMessage()], 401);
                }
            }
            /* END VIDEO UPLOAD */
                //URUNG tes fcm
            $organization_id = $request->organization_id ?? null;
            $tokenUser = DB::table('users')
                // ->when($auth->role!=1, function ($q) use ($auth) {
                //     return $q->where('company_id', $auth->company_id);
                // })
                // ->when($request->type == 1 && $organization_id, function ($query) use ($organization_id) {
                //     return $query->where('organization_id', $organization_id);
                // })
                ->where('token','!=',"")
                ->pluck('token')->toArray();
            if($tokenUser) {
                $result = fcm()->to($tokenUser)
                ->timeToLive(0)
                ->priority('high')
                ->notification([
                    'title' => 'Hai, ada course baru nih buat kamu!',
                    'body' => $request->title,
                ])
                ->data([
                    'title' => 'Hai, ada course baru nih buat kamu!',
                    'body' => $request->title,
                ])
                ->send();
            }
            if($request->filled('image')) {
                $imgName='';
                $baseString = explode(';base64,', $request->image);
                $image = base64_decode($baseString[1]);
                $image = imagecreatefromstring($image);

                $ext = explode('/', $baseString[0]);
                $ext = $ext[1];
                $imgName = 'course_'.uniqid().'.'.$ext;
                if($ext=='png'){
                    imagepng($image,public_path().'/files/'.$imgName,8);
                } else {
                    imagejpeg($image,public_path().'/files/'.$imgName,20);
                }
                DB::table('courses')->where('id', $courseGetId)->update(['image' => $imgName]);
            }

            foreach ($request->questions as $question) {
                if (!$question["answers"]) continue;
                $qId=DB::table('test_questions')->insertGetId([
                    'course_id' => $courseGetId,
                    'is_pre_test' => $question['is_pre_test'],
                    'description' => $question['description'],
                ]);
                foreach ($question['answers'] as $answer) {
                    $ans = DB::table('test_answers')->insert([
                        'test_question_id' => $qId,
                        'name' => $answer['name'],
                        'is_true' => $answer['is_true'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
        return response()->json([
            'data' => $courseGetId,
            'message' => 'Data berhasil disimpan!'
        ], $this->successStatus);
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

    public function detailsCourse($id)
    {
        $course = Course::where('id', $id)->first();
        return response()->json(['success' => $course], $this->successStatus);
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
        $title = $request->title;
        $description = $request->description;
        $image = '';
        $video = $request->video;
        $link = $request->link;

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'file_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'course_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
        }

        $course = Course::find($id);
        $course->title = $title;
        $course->description = $description;
        $course->image = $imgName;
        $course->video = $video;
        $course->file = 'files/'.$filename;
        $course->link = $link;
        $course->save();


        // DB::commit();
        return response()->json([
            'success'=>$course,
            'message'=>'update successfully'],
        $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $qId=DB::table('courses')->where('id',$id)->select('id')->get();
        $tq = DB::table('test_questions')->where('course_id',$qId[0]->id)->select('id')->get();
        // $ta = DB::table('user_scores')->where('course_id',$qId[0]->id)->select('id')->get();
        DB::table('user_scores')->where('course_id',$qId[0]->id)->select('id')->delete();
        DB::table('test_answers')->where('test_question_id',$tq[0]->id)->select('id')->delete();
        DB::table('test_questions')->where('course_id',$qId[0]->id)->select('id')->delete();
        DB::table('courses')->where('id',$id)->select('id')->delete();

        // // return $qId;
        return response()->json(['message' => 'delete successfully']);
    }
}
