<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crossfunction;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;


class CrossfunctionController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = auth()->user();
        $data = Crossfunction::with(['company','organization'])
              -> when($auth->role!=1, function($q) use ($auth) {
                  return $q->where('company_id', $auth->company_id);
              })
              ->where('organization_id', $request->organization_id)
              ->orderBy('id')
              ->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cross()
    {
        return response()->json(['data' => Crossfunction::get()]);
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
            'organization_id'   => 'required',
            'image'             => 'required',
            'title'             => 'required',
            'description'       => 'required',
            'file'              => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],401);
        }

        $auth = auth()->user();

        //* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'crossfunction_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */
        try {
            DB::beginTransaction();
            $crossGetId = DB::table('crossfunctions')->insertGetId([
                'company_id'        => $auth->company_id,
                'organization_id'   => $request->organization_id ?? null,
                'title'             => $request->title,
                'image'             => '',
                'description'       => $request->description,
                'file'              => 'files/'.$filename,
                // 'file'              => env('APP_URL') . '/' . $url,
            ]);

            if($request->filled('image')) {
                $imgName='';
                $baseString = explode(';base64,', $request->image);
                $image = base64_decode($baseString[1]);
                $image = imagecreatefromstring($image);

                $ext = explode('/', $baseString[0]);
                $ext = $ext[1];
                $imgName = 'cross_'.uniqid().'.'.$ext;
                if($ext=='png'){
                    imagepng($image,public_path().'/files/'.$imgName,8);
                } else {
                    imagejpeg($image,public_path().'/files/'.$imgName,20);
                }
                DB::table('crossfunctions')->where('id', $crossGetId)->update(['image' => $imgName]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $crossGetId,
            'message'   => 'Data Berhasil disimpan!'
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
        $data = DB::table('crossfunctions')->where('id', $id)->first();
        return response()->json(['success'=>$data],$this->successStatus);
    }

    public function status($id)
    {
        $data = Crossfunction::where('id',$id)->first();
        // dd($data->title);

        $st_sekarang = $data->status;

        if ($st_sekarang == 1) {
            $sop = Crossfunction::find($id);
            $sop->status = 0;
            $sop->save();
        }else{
            $sop = Crossfunction::find($id);
            $sop->status = 1;
            $sop->save();
        }

        return response()->json(['message' => 'Data Update Successfully'],$this->successStatus);
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
        $title = $request->title;
        $description = $request->description;
        $image = '';

        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',')+1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'crossfunction_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'crossfunction_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
        }

        $course = Crossfunction::find($id);
        $course->title = $title;
        $course->description = $description;
        $course->image = $imgName;
        $course->file = 'files/'.$filename;
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
        Crossfunction::destroy($id);

        return response()->json(['message', 'Data berhasil di hapus!']);
    }
}
