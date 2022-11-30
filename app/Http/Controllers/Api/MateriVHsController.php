<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MateriVhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;
use Symfony\Component\HttpKernel\Exception\HttpException;


class MateriVHsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadfile($id)
    {
        $materi = DB::table('materi_vhs')->select('file')->where('id',$id)->first();
        // $path = public_path('file/materivhs/file/'.$sop->file);
        // $response = response()->download($path);
        // ob_end_clean();
        return response()->json(['data' => $materi->file]);
    }
    public function index()
    {
        try {
            // $data=DB::table('materi_vhs')
            //         ->join('jadwalvhs','jadwalvhs.id','materi_vhs.jadwal_id')
            //         ->select('materi_vhs.*','jadwalvhs.name as jadwal_vhs_name')
            //         ->orderBy('materi_vhs.id', 'desc')
            //         ->get();
            $data=MateriVhs::join('jadwalvhs',function($join){
                $join->on('materi_vhs.jadwal_id','=','jadwalvhs.id');
            })->select('materi_vhs.*','jadwalvhs.name as jadwal_vhs_name')->orderBy('materi_vhs.id','desc')->get();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'desc' => 'required',
            'type' => 'required',
            'jadwal_id' => 'required',
            'image' => 'image|max:2084|nullable',
            'file' => 'file|nullable',
            'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
                
        /* PREPARE image UPLOAD */
        if ($request->hasFile('image')) {
            $imageEXT    = $request->file('image')->getClientOriginalName();
            $filename   = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('image')->getClientOriginalExtension();
            $fileimage = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('image')->move(public_path('file/materivhs/image'), $fileimage);
        }else {
            $fileimage = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE file UPLOAD */
        if ($request->hasFile('file')) {
            $fileEXT    = $request->file('file')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('file')->getClientOriginalExtension();
            $fileUp     = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('file')->move(public_path('file/materivhs/file'), $fileUp);
        }else {
            $fileUp = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE video UPLOAD */
        if ($request->hasFile('video')) {
            $videoEXT       = $request->file('video')->getClientOriginalName();
            $filename       = pathinfo($videoEXT, PATHINFO_FILENAME);
            $EXT            = $request->file('video')->getClientOriginalExtension();
            $fileVideo      = $filename. '_'.time().'.' .$EXT;
            $path           = $request->file('video')->move(public_path('file/materivhs/video'), $fileVideo);
        }else {
            $fileVideo = 'error';
        }        
        /* END image UPLOAD */
        // dd($fileimage);
        // dd($fileUp);
        // dd($fileVideo);
        try {
            $data= MateriVhs::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'jadwal_id' => $request->jadwal_id,
                'image' => $fileimage,
                'file' => $fileUp,
                'video' => $fileVideo,
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
        // $data=DB::table('zooms_vhs')
        //         ->join('jadwalvhs','jadwalvhs.id','zooms_vhs.jadwal_id')
        //         ->select('zooms_vhs.id as zoom_id','jadwalvhs.id as jadwalvhs_id','zooms_vhs.name as zoom_name','jadwalvhs.name as jadwalvhs_name','zooms_vhs.*','jadwalvhs.*')
        //         ->where('zooms_vhs.id',$id)
        //         ->first();
        // if ($data) {
        //     return response()->json(['success' => $data], $this->successStatus);
        // } else {
        //     return response()->json(['error' => "data not found"], $this->errorStatus);
        // }

        try {
            $data=MateriVhs::join('jadwalvhs',function($join){
                $join->on('materi_vhs.jadwal_id','=','jadwalvhs.id');
            })
                ->select('materi_vhs.*','jadwalvhs.name as jadwal_vhs_name')
                ->where('materi_vhs.id',$id)
                ->first();
            return response()->json(
                [
                    'success' => $data
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
        //TODO not fixed yet
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'desc' => 'required',
        //     'type' => 'required',
        //     'jadwal_id' => 'required',
        //     'image' => 'image|max:2084|nullable',
        //     'file' => 'file|nullable',
        //     'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|nullable'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error'=>$validator->errors()], 401);
        // }
                
        /* PREPARE image UPLOAD */
        if ($request->hasFile('image')) {
            $imageEXT    = $request->file('image')->getClientOriginalName();
            $filename   = pathinfo($imageEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('image')->getClientOriginalExtension();
            $fileimage = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('image')->move(public_path('file/materivhs/image'), $fileimage);
        }else {
            $fileimage = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE file UPLOAD */
        if ($request->hasFile('file')) {
            $fileEXT    = $request->file('file')->getClientOriginalName();
            $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
            $EXT        = $request->file('file')->getClientOriginalExtension();
            $fileUp     = $filename. '_'.time().'.' .$EXT;
            $path       = $request->file('file')->move(public_path('file/materivhs/file'), $fileUp);
        }else {
            $fileUp = 'error';
        }        
        /* END image UPLOAD */

        /* PREPARE video UPLOAD */
        if ($request->hasFile('video')) {
            $videoEXT       = $request->file('video')->getClientOriginalName();
            $filename       = pathinfo($videoEXT, PATHINFO_FILENAME);
            $EXT            = $request->file('video')->getClientOriginalExtension();
            $fileVideo      = $filename. '_'.time().'.' .$EXT;
            $path           = $request->file('video')->move(public_path('file/materivhs/video'), $fileVideo);
        }else {
            $fileVideo = 'error';
        }        
        /* END image UPLOAD */
       
        try {
            $data= MateriVhs::findOrfail($id)->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'jadwal_id' => $request->jadwal_id,
                'image' => $fileimage,
                'file' => $fileUp,
                'video' => $fileVideo,
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
            $delete = MateriVhs::findOrFail($id);
            $pathfile = app_path("file/materivhs/file/{$delete->file}");
            $pathImage = app_path("file/materivhs/image/{$delete->image}");
            $pathVideo = app_path("file/materivhs/video/{$delete->video}");
            if(File::exists($pathfile) || File::exists($pathImage) || File::exists($pathVideo)){
                unlink($pathfile);
                unlink($pathImage);
                unlink($pathVideo);
            };
            $delete->forceDelete();
            return response()->json([
                'message' => 'Data Berhasil di Hapus'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }
}
