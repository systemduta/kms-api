<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $user = auth()->user();
        // $organization = Organization::query()
        //     ->when(($user && $user->role!=1), function ($q) use ($user) {
        //         return $q->where('company_id', $user->company_id);
        //     })
        //     ->get();
        $organization = DB::table('organizations')
            ->join('companies','organizations.company_id','=','companies.id')
            // ->when(($user && $user->role!=1), function ($q) use ($user) {
            //     return $q->where('company_id', $user->company_id);
            //     })
            ->select('organizations.*','companies.name as name_company')
            ->get();
        return response()->json(['data' => $organization]);
    }

//    public function get_organization_by_company(Request $request)
//    {
//        $company_id = auth()->user->company_id;
//        $data = DB::table('organizations')
//            ->where('company_id', $company_id)
//            ->selectRaw('id,code,name,is_str')->get();
//        return response()->json(['data' => $data]);
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
            'company_id' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = auth()->user();

        $organization = new Organization();
        $organization->company_id = $request->company_id ?? $user->company_id;
        $organization->parent_id = null;
        $organization->name = $request->name;
        $organization->code = $request->code;
        $organization->iterasi = 0;
        $organization->is_str = $request->is_str ?? 0;
        $organization->save();

        return response()->json([
            'data' => $organization,
            'message' => 'Data berhasil disimpan!'
        ], 200);
    }

    public function show(Organization $organization)
    {
        return response()->json(['data' => $organization]);
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

    public function update(Request $request, Organization $organization)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'code' => 'string',
            'company_id' => 'numeric|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = auth()->user();

        $organization->company_id = $request->company_id ?? $user->company_id;
        $organization->parent_id = null;
        $organization->name = $request->name;
        $organization->code = $request->code;
        $organization->iterasi = 0;
        $organization->is_str = $request->is_str ?? 0;
        $organization->save();

        return response()->json([
            'data' => $organization,
            'message' => 'Data berhasil diupdate!'
        ], 200);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json(['message' => 'Delete Successfully']);
    }
}
