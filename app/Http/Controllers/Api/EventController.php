<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Validator;
use DB;

class EventController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DB::table('events')->where('company_id', $request->company_id)->orderBy('id','DESC')->get();
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
            'company_id' => 'required',
            'image' => 'required',
            'title' => 'required',
            'description' => 'required',
            'link' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        DB::beginTransaction();
        $eventGetId=DB::table('events')->insertGetId([
            'company_id' => $request->company_id,
            'image' => '',
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link
        ]);
        
        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'event_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
            DB::table('events')->where('id', $eventGetId)->update(['image' => $imgName]);
        }
        
        $tokenUser = DB::table('users')->where('company_id', $request->company_id)
        ->where('token','!=',"")
        ->pluck('token')->toArray();
        if($tokenUser) {
            $tokens=(Array) $tokenUser;

            $result = fcm()->to($tokenUser)
            ->timeToLive(0)
            ->priority('normal')
            ->notification([
                'title' => 'Hai, ada event baru nih buat kamu!',
                'body' => $request->title,
            ])
            ->data([
                'title' => 'Hai, ada event baru nih buat kamu!',
                'body' => $request->title,
            ])
            ->send();
        }
        
        DB::commit();
        return response()->json(['data' => $eventGetId, 'message' => 'Data berhasil disimpan!'], $this->successStatus);

        $input = [
            'company_id' => $request->company_id,
            'image' => $request->image,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link
        ];
        $event = Event::create($input);
        $success['company_id'] =  $event->company_id;
        $success['title'] =  $event->title;
        $success['description'] =  $event->description;

        return response()->json(['success'=>$success], $this->successStatus);
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

    public function detailsEvent($id)
    {
        $event = Event::where('id', $id)->first();
        return response()->json(['success' => $event], $this->successStatus);
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
        $link = $request->link;
        $image = '';

        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'event_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
        }

        $event = Event::find($id);
        $event->title = $title;
        $event->description = $description;
        $event->image = $imgName;
        $event->link = $link;
        $event->save();

        // DB::commit();
        return response()->json([
            'success'=>$event,
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
        $event = Event::find($id);
        $event->delete();
        return response()->json(['message' => 'delete successfully']);
    }
}
