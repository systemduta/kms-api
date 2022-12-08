<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Crossfunction;
use App\Models\Lampiran;
use App\Models\Sop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SOPController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getall()
    {
        $user = auth()->user();
        return response()->json(
            ['data' => Company::when(($user && $user->role!=1), function ($q) use ($user) {
                return $q->where('id', $user->company_id);
                })->orderBy('id','DESC')->get()]
        );
    }

    public function getOrg($id)
    {
        $detailCompany =  Company::where('id',$id)->orderBy('id','ASC')->get();
        $listDivision = DB::table('companies')
            ->join('organizations','organizations.company_id','=','companies.id')
            ->where('companies.id',$id)
            ->get();
        return response()->json(
            [
                'detailcompany' =>$detailCompany,
                'listorganizations' =>$listDivision,
            ]
        );
    }

    public function index(Request $request)
    {
        $auth = auth()->user();
        $data = Sop::with(['company','organization'])
                ->when($auth->role!=1, function ($q) use ($auth) {
                    return $q->where('company_id', $auth->company_id);
                })
                ->where('organization_id', $request->organization_id)
                ->orderBy('id', 'DESC')
                ->get();
        return response()->json(['data' => $data]);
    }

    public function sop()
    {
        return response()->json(['data' => Sop::get()]);
    }

    public function sopdown($id)
    {
        $sop = DB::table('sops')->select('file')->where('id',$id)->first();
        return response()->json(['data' => $sop->file]);
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
        error_reporting(0);
        $validator = Validator::make($request->all(), [
            'organization_id'   => 'required',
            'company_id'        => 'required',
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
        $filename = 'sop_'.Str::random(10).'.'.$ext;
        Storage::disk('public')->put('files/'.$filename, base64_decode($file));
        /* END FILE UPLOAD */

        try {
            DB::beginTransaction();
            $sopGetId = DB::table('sops')->insertGetId([
                // 'company_id'        => $auth->company_id,
                'company_id'        => $request->company_id,
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
                $imgName = 'sop_'.uniqid().'.'.$ext;
                if($ext=='png'){
                    imagepng($image,public_path().'/files/'.$imgName,8);
                } else {
                    imagejpeg($image,public_path().'/files/'.$imgName,20);
                }
                DB::table('sops')->where('id', $sopGetId)->update(['image' => $imgName]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }

        return response()->json([
            'data'      => $sopGetId,
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
        $data = DB::table('sops')->where('id', $id)->first();
        return response()->json(['success' => $data], $this->successStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $data = Sop::where('id',$id)->first();
        // dd($data->title);

        $st_sekarang = $data->status;

        if ($st_sekarang == 1) {
            $sop = Sop::find($id);
            $sop->status = 2;
            $sop->save();
        }else{
            $sop = Sop::find($id);
            $sop->status = 1;
            $sop->save();
        }

        return response()->json(['message' => 'Data Update Successfully'],$this->successStatus);
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
        $companyid = $request->company_id;
        $organizationid = $request->organization_id;
        $image = '';

        if ($request->hasFile('file')) {
            /* START FILE UPLOAD */
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',')+1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = 'sop_'.Str::random(10).'.'.$ext;
            Storage::disk('public')->put('files/'.$filename, base64_decode($file));
            /* END FILE UPLOAD */

            $updatefile= Sop::findOrFail($id)->update([
                'file'              => 'files/'.$filename,
            ]);
        }        

        if($request->filled('image')) {
            $imgName='';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'sop_'.uniqid().'.'.$ext;
            if($ext=='png'){
                imagepng($image,public_path().'/files/'.$imgName,8);
            } else {
                imagejpeg($image,public_path().'/files/'.$imgName,20);
            }
            $updateimage= Sop::findOrFail($id)->update([
                'image'        => $imgName,
            ]);
        }

        // $course = Sop::find($id);
        // $course->title = $title;
        // $course->description = $description;
        // $course->company_id = $companyid;
        // $course->organization_id = $organizationid;
        // $course->image = $imgName;
        // $course->file = 'files/'.$filename;
        // $course->save();

        $course= Sop::findOrFail($id)->update([
            'company_id'        => $request->company_id,
            'organization_id'   => $request->organization_id ?? null,
            'title'             => $request->title,
            // 'image'             => '',
            'description'       => $request->description,
            // 'file'              => 'files/'.$filename,
        ]);

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
        Sop::destroy($id);

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
