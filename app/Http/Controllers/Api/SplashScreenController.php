<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SplashScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class SplashScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => SplashScreen::all()]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SplashScreen  $splashScreen
     * @return \Illuminate\Http\Response
     */
    public function show(SplashScreen $splashScreen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SplashScreen  $splashScreen
     * @return \Illuminate\Http\Response
     */
    public function edit(SplashScreen $splashScreen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SplashScreen  $splashScreen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SplashScreen $splashScreen)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpg,jpeg,bmp,png|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $splash_name = null;
        $splash_path = null;
        if ($request->hasFile('image')) {
            $splash = $request->image;
            $splash_path = 'splash/';
            $splash_name = Str::random(20).'.'.$splash->getClientOriginalExtension();
            try {
                Storage::disk('public')->put($splash_path.$splash_name, file_get_contents($splash));
            } catch (Exception $e){
                return response()->json(['error'=>$e->getMessage()], 401);
            }
        }
        $splashScreen->image = $splash_path.$splash_name;
        $splashScreen->save();

        return response()->json([
            'success'=>'Success',
            'message'=>'update successfully'],
            200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SplashScreen  $splashScreen
     * @return \Illuminate\Http\Response
     */
    public function destroy(SplashScreen $splashScreen)
    {
        //
    }
}
