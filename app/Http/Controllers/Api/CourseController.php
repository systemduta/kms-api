<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DB::table('courses')->where('organization_id', $request->organization_id)->orderBy('id', 'DESC')->get();
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'video' => 'required',
            'file' => 'required',
            'link' => 'required',
            'type' => 'required',
            'video' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        /* START VIDEO UPLOAD */
        $video64 = $request->video;
        $ext = explode('/', explode(':', substr($video64, 0, strpos($video64, ';')))[1])[1];
        $replace = substr($video64, 0, strpos($video64, ',')+1);
        $video = str_replace($replace, '', $video64);
        $video = str_replace(' ', '+', $video);
        $videoname = Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$videoname, base64_decode($video));
        /* END VIDEO UPLOAD */

        DB::beginTransaction();
        $courseGetId=DB::table('courses')->insertGetId([
            'organization_id' => $request->organization_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => '',
            'video' => 'files/'.$videoname,
            'file' => 'files/'.$filename,
            'link' => $request->link,
            'type' => $request->type
        ]);
        
        $tokenUser = DB::table('users')->where('organization_id', $request->organization_id)
        ->where('token','!=',"")
        ->pluck('token')->toArray();
        if($tokenUser) {
            $tokens=(Array) $tokenUser;

            $result = fcm()->to($tokenUser)
            ->timeToLive(0)
            ->priority('normal')
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
        DB::commit();
        return response()->json([
            'data' => $courseGetId, 
            'message' => 'Data berhasil disimpan!',
            'token' => $tokenUser
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
        $file = $request->file;
        $link = $request->link;

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
        $course->file = $file;
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
    public function destroy($id)
    {
        $course = Course::find($id);
        $course->delete();
        return response()->json(['message' => 'delete successfully']);
    }
}
