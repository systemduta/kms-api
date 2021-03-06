<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use Exception;
use File;

class VhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Vhs::orderBy('created_at', 'desc')->get();
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
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'image|max:2084|nullable',
            'video' => 'file|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        /* PREPARE VIDEO UPLOAD */
        $video_name = null;
        $video_path = null;
        $video = null;
        if ($request->hasFile('video')) {
            $video = $request->video;
            $video_path = 'files/vhs/video/';
            $video_name = Str::random(20).'.'.$video->getClientOriginalExtension();
        }
        /* END PREPARE VIDEO UPLOAD */

        /* START VIDEO UPLOAD */
        $thumbnail_name = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail_name = Storage::disk('public')->put('files/vhs/thumbnail', $request->thumbnail);
        }
        /* END VIDEO UPLOAD */

        $vhs = new Vhs();
        $vhs->title = $request->title;
        $vhs->description = $request->description;
        $vhs->thumbnail = $thumbnail_name;
        $vhs->video = $video_path.$video_name;
        $vhs->save();

        /* START VIDEO UPLOAD */
        if ($request->hasFile('video')) {
            try {
                Storage::disk('public')->put($video_path.$video_name, file_get_contents($video));
            } catch (Exception $e){
                return response()->json(['error'=>$e->getMessage()], 401);
            }
        }
        /* END VIDEO UPLOAD */

        return response()->json(['data' => $vhs, 'message' => 'Data berhasil disimpan!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function show(Vhs $vhs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function edit(Vhs $vhs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\\Models\\Vhs  $vhs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vhs $vhs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vhs = Vhs::find($id);
        $video_path = public_path().'/files/'.$vhs->video;
        $thumbnail_path = public_path().'/files/'.$vhs->thumbnail;
        $res = File::delete($video_path, $thumbnail_path);
        $vhs->delete();
        return response()->json(['message' => 'delete successfully']);
    }
}
