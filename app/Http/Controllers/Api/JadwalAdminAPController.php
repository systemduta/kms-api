<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalUserVhs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalAdminAPController extends Controller
{
    public $successStatus = 200; //variabel ayng akan dipangggil saat operasi sukses dilakukan
    public $errorStatus = 403; //variabel yang akan di panggil saat operasi gagal dilakukan
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUser($id)
    {
        $user = auth()->user();
        $data = DB::table('jadwal_user_vhs')
                ->join('users','users.id','=','jadwal_user_vhs.user_id')
                ->join('jadwalvhs','jadwalvhs.id','=','jadwal_user_vhs.jadwal_id')
                ->where('jadwal_user_vhs.company_id',$user->company_id)                     
                ->where('jadwal_user_vhs.jadwal_id',$id)                     
                ->get();
        return response()->json([
                    'data'=>$data,  
                    'message'=>'update successfully'],
                $this->successStatus);
    }

    public function index()
    {
        $user = auth()->user();
        $quota = DB::table('jadwalvhs')
                ->leftJoin('quotaaps','quotaaps.jadwal_id','=','jadwalvhs.id')
                ->leftJoin('companies','companies.id','=','quotaaps.company_id')
                ->select('quotaaps.id','companies.id as comid','companies.name as comname','jadwalvhs.id as jadwalid','jadwalvhs.name','jadwalvhs.batch','quotaaps.quota','jadwalvhs.start','jadwalvhs.end','jadwalvhs.quota as quotautama','jadwalvhs.type')
                ->where('quotaaps.company_id',$user->company_id)
                ->orWhere('jadwalvhs.isCity',3)
                ->orWhere('jadwalvhs.isCity',4)
                ->get();
        return response()->json([
                        'data'=>$quota,  
                        'message'=>'update successfully'],
                    $this->successStatus);
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
        $user = auth()->user();
        $validator = Validator::make($request->all(),[
            'user_id'     =>'required',
            'jadwal_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],400);
        }

        try {
            $cekType = DB::table('jadwalvhs')->where('id',$request->jadwal_id)->select('isCity')->first();
            $userDiv = DB::table('users')
                        ->join('organizations','organizations.id','=','users.organization_id')
                        ->where('users.id',$request->user_id)
                        ->select('organizations.isAdm')
                        ->first();
            $userDiv = intval($userDiv->isAdm);
            
            if ($cekType->isCity == '1' || $cekType->isCity == '2') {
               
                $maxquota = DB::table('quotaaps')->where('jadwal_id',$request->jadwal_id)->where('company_id',$user->company_id)->select('quota')->first();

                $userquota = DB::table('jadwal_user_vhs')
                            ->where('jadwal_id',$request->jadwal_id)
                            ->where('company_id',$user->company_id)
                            ->where('isAllow',1)
                            ->count();
    
                $sisa =  intVal($maxquota->quota) - intval($userquota);
                
                if($sisa<=0){
                    return response()->json([
                        'error'=>"Anda telah mendaftarkan lebih dari quota hubungi administrator untuk informasi lengkap"],
                    $this->errorStatus);
                }else{
                    $isIn = DB::table('jadwal_user_vhs')
                            ->where('jadwal_id',$request->jadwal_id)
                            ->where('company_id',$user->company_id)
                            ->where('user_id',$request->user_id)
                            ->count();
                    
                    if ($isIn==1) {
                        return response()->json([
                            'error'=>"User telah didaftarkan"],
                        $this->errorStatus);
                    } else {
                        $typeVhs = DB::table('jadwalvhs')
                                    ->where('id',$request->jadwal_id)
                                    ->select('type')
                                    ->first();
                        
                        $cekBasic = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isBasic')
                                    ->first();
                        $cekBasic = intval($cekBasic->isBasic);

                        $cekClass = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isClass')
                                    ->first();
                        $cekClass = intval($cekClass->isClass);
                        
                        $cekCamp = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isCamp')
                                    ->first();
                        $cekCamp = intval($cekCamp->isCamp);
                        
                        $cekAcademy = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isAcademy')
                                    ->first();
                        $cekAcademy = intval($cekAcademy->isAcademy);

                        if ($typeVhs->type == "1VHS Basic") {
                            $data = DB::table('jadwal_user_vhs')->insertGetId([
                                "user_id"=>$request->user_id,
                                "jadwal_id"=>$request->jadwal_id,
                                "company_id"=>$user->company_id,
                                "isAllow"=>0,
                                "is_take"=>0,
                            ]);
                            return response()->json([
                                'success'=>$data,
                                'message'=>'insert successfully'],
                            $this->successStatus);
                        }
                        if ($typeVhs->type =="1VHS Class" && $userDiv == 1) {
                            if ($cekBasic==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Class" && $userDiv==0) {
                            return response()->json([
                                'error' => [
                                    1 => "User belum pernah mengikuti 1VHS Basic",
                                    2 => "User termasuk dalam tipe karyawan lapangan"
                                ]
                            ],$this->errorStatus);
                            
                        }
                        if ($typeVhs->type=="1VHS Camp" && $userDiv ==0) {
                            if ($cekBasic==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Camp" && $userDiv == 1) {
                            if ($cekBasic==1 && $cekClass==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                        2 => "User belum pernah mengikuti 1VHS Class"
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Academy") {
                           if ($userDiv == 0) {
                                if ($cekBasic== 1 && $cekCamp==1) {
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                            2 => "User belum pernah mengikuti 1VHS Camp"
                                        ]
                                    ],$this->errorStatus);
                                }
                           } else {
                                if ($cekBasic==1 && $cekClass==1 && $cekCamp==1) {
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                            2 => "User belum pernah mengikuti 1VHS Class",
                                            3 => "User belum pernah mengikuti 1VHS Camp"
                                        ]
                                    ],$this->errorStatus);
                                }
                           }
                        }                                              
                    }                   
                }    
            } else {
                if($cekType->isCity =='3')
                {
                    $maxquota = DB::table('jadwalvhs')
                                ->where('id',$request->jadwal_id)
                                ->select('quota')
                                ->first();

                    $userquota = DB::table('jadwal_user_vhs')
                                ->where('jadwal_id',$request->jadwal_id)
                                ->where('company_id',$user->company_id)
                                ->count();
    
                    $sisa =  intVal($maxquota->quota) - intval($userquota);

                    if($sisa<=0){
                        return response()->json([
                            'error'=>"Kuota Telah Terpenuhi"],
                        $this->errorStatus);
                    } else {
                        $isIn = DB::table('jadwal_user_vhs')
                                ->where('jadwal_id',$request->jadwal_id)
                                ->where('company_id',$user->company_id)
                                ->where('user_id',$request->user_id)
                                ->count();
    
                        if ($isIn==1) {
                            return response()->json([
                                'error'=>"User telah didaftarkan"],
                            $this->errorStatus);
                        } else {                         
                            $typeVhs = DB::table('jadwalvhs')
                                    ->where('id',$request->jadwal_id)
                                    ->select('type')
                                    ->first();
                
                            $cekBasic = DB::table('users')
                                        ->where('id',$request->user_id)
                                        ->select('isBasic')
                                        ->first();
                            $cekBasic = intval($cekBasic->isBasic);

                            $cekClass = DB::table('users')
                                        ->where('id',$request->user_id)
                                        ->select('isClass')
                                        ->first();
                            $cekClass = intval($cekClass->isClass);
                            
                            $cekCamp = DB::table('users')
                                        ->where('id',$request->user_id)
                                        ->select('isCamp')
                                        ->first();
                            $cekCamp = intval($cekCamp->isCamp);
                            
                            $cekAcademy = DB::table('users')
                                        ->where('id',$request->user_id)
                                        ->select('isAcademy')
                                        ->first();
                            $cekAcademy = intval($cekAcademy->isAcademy);

                            if ($typeVhs->type == "1VHS Basic") {
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            }
                            if ($typeVhs->type =="1VHS Class" && $userDiv == 1) {
                                if ($cekBasic==1) {       
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                        ]
                                    ],$this->errorStatus);
                                }
                            }
                            if ($typeVhs->type=="1VHS Class" && $userDiv==0) {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                        2 => "User termasuk dalam tipe karyawan lapangan"
                                    ]
                                ],$this->errorStatus);
                            }
                            if ($typeVhs->type=="1VHS Camp" && $userDiv ==0) {
                                if ($cekBasic==1) {       
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                        ]
                                    ],$this->errorStatus);
                                }
                            }
                            if ($typeVhs->type=="1VHS Camp" && $userDiv == 1) {
                                if ($cekBasic==1 && $cekClass==1) {       
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                            2 => "User belum pernah mengikuti 1VHS Class"
                                        ]
                                    ],$this->errorStatus);
                                }
                            }
                            if ($typeVhs->type=="1VHS Academy") {
                               if ($userDiv == 0) {
                                    if ($cekBasic== 1 && $cekCamp==1) {
                                        $data = DB::table('jadwal_user_vhs')->insertGetId([
                                            "user_id"=>$request->user_id,
                                            "jadwal_id"=>$request->jadwal_id,
                                            "company_id"=>$user->company_id,
                                            "isAllow"=>0,
                                            "is_take"=>0,
                                        ]);
                                        return response()->json([
                                            'success'=>$data,
                                            'message'=>'insert successfully'],
                                        $this->successStatus);
                                    } else {
                                        return response()->json([
                                            'error' => [
                                                1 => "User belum pernah mengikuti 1VHS Basic",
                                                2 => "User belum pernah mengikuti 1VHS Camp"
                                            ]
                                        ],$this->errorStatus);
                                    }
                               } else {
                                    if ($cekBasic==1 && $cekClass==1 && $cekCamp==1) {
                                        $data = DB::table('jadwal_user_vhs')->insertGetId([
                                            "user_id"=>$request->user_id,
                                            "jadwal_id"=>$request->jadwal_id,
                                            "company_id"=>$user->company_id,
                                            "isAllow"=>0,
                                            "is_take"=>0,
                                        ]);
                                        return response()->json([
                                            'success'=>$data,
                                            'message'=>'insert successfully'],
                                        $this->successStatus);
                                    } else {
                                        return response()->json([
                                            'error' => [
                                                1 => "User belum pernah mengikuti 1VHS Basic",
                                                2 => "User belum pernah mengikuti 1VHS Class",
                                                3 => "User belum pernah mengikuti 1VHS Camp",
                                            ]
                                        ],$this->errorStatus);
                                    }
                               }
                            }  
                        }                   
                    }   
                } else {
                    $isIn = DB::table('jadwal_user_vhs')
                            ->where('jadwal_id',$request->jadwal_id)
                            ->where('company_id',$user->company_id)
                            ->where('user_id',$request->user_id)
                            ->count();
                
                    if ($isIn) {
                        return response()->json([
                            'error'=>"User telah didaftarkan"],
                        $this->errorStatus);
                    } else {
                        $typeVhs = DB::table('jadwalvhs')
                        ->where('id',$request->jadwal_id)
                        ->select('type')
                        ->first();
    
                        $cekBasic = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isBasic')
                                    ->first();
                        $cekBasic = intval($cekBasic->isBasic);

                        $cekClass = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isClass')
                                    ->first();
                        $cekClass = intval($cekClass->isClass);
                        
                        $cekCamp = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isCamp')
                                    ->first();
                        $cekCamp = intval($cekCamp->isCamp);
                        
                        $cekAcademy = DB::table('users')
                                    ->where('id',$request->user_id)
                                    ->select('isAcademy')
                                    ->first();
                        $cekAcademy = intval($cekAcademy->isAcademy);

                        if ($typeVhs->type == "1VHS Basic") {
                            $data = DB::table('jadwal_user_vhs')->insertGetId([
                                "user_id"=>$request->user_id,
                                "jadwal_id"=>$request->jadwal_id,
                                "company_id"=>$user->company_id,
                                "isAllow"=>0,
                                "is_take"=>0,
                            ]);
                            return response()->json([
                                'success'=>$data,
                                'message'=>'insert successfully'],
                            $this->successStatus);
                        }
                        if ($typeVhs->type =="1VHS Class" && $userDiv == 1) {
                            if ($cekBasic==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Class" && $userDiv==0) {
                            return response()->json([
                                'error' => [
                                    1 => "User belum pernah mengikuti 1VHS Basic",
                                    2 => "User termasuk dalam tipe karyawan lapangan"
                                ]
                            ],$this->errorStatus);
                        }
                        if ($typeVhs->type=="1VHS Camp" && $userDiv ==0) {
                            if ($cekBasic==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Camp" && $userDiv == 1) {
                            if ($cekBasic==1 && $cekClass==1) {       
                                $data = DB::table('jadwal_user_vhs')->insertGetId([
                                    "user_id"=>$request->user_id,
                                    "jadwal_id"=>$request->jadwal_id,
                                    "company_id"=>$user->company_id,
                                    "isAllow"=>0,
                                    "is_take"=>0,
                                ]);
                                return response()->json([
                                    'success'=>$data,
                                    'message'=>'insert successfully'],
                                $this->successStatus);
                            } else {
                                return response()->json([
                                    'error' => [
                                        1 => "User belum pernah mengikuti 1VHS Basic",
                                        2 => "User belum pernah mengikuti 1VHS Class"
                                    ]
                                ],$this->errorStatus);
                            }
                        }
                        if ($typeVhs->type=="1VHS Academy") {
                           if ($userDiv == 0) {
                                if ($cekBasic== 1 && $cekCamp==1) {
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                            2 => "User belum pernah mengikuti 1VHS Camp",
                                        ]
                                    ],$this->errorStatus);
                                }
                           } else {
                                if ($cekBasic==1 && $cekClass==1 && $cekCamp==1) {
                                    $data = DB::table('jadwal_user_vhs')->insertGetId([
                                        "user_id"=>$request->user_id,
                                        "jadwal_id"=>$request->jadwal_id,
                                        "company_id"=>$user->company_id,
                                        "isAllow"=>0,
                                        "is_take"=>0,
                                    ]);
                                    return response()->json([
                                        'success'=>$data,
                                        'message'=>'insert successfully'],
                                    $this->successStatus);
                                } else {
                                    return response()->json([
                                        'error' => [
                                            1 => "User belum pernah mengikuti 1VHS Basic",
                                            2 => "User belum pernah mengikuti 1VHS Class",
                                            3 => "User belum pernah mengikuti 1VHS Camp",
                                        ]
                                    ],$this->errorStatus);
                                }
                           }
                        } 
                    }
                }
            }                   
        } catch (\Exception $th) {
            return response()->json([
                'error'=>$th->getMessage(),],
            $this->errorStatus);
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
        $user = auth()->user();
        $data = DB::table('jadwal_user_vhs')
                    ->join('users','users.id','=','jadwal_user_vhs.user_id')
                    ->join('jadwalvhs','jadwalvhs.id','=','jadwal_user_vhs.jadwal_id')
                    ->join('companies','companies.id','=','jadwal_user_vhs.company_id')
                    ->where('jadwal_user_vhs.jadwal_id',$id)
                    ->where('jadwal_user_vhs.company_id',$user->company_id)
                    ->select('users.name as username','jadwalvhs.name as jadwalVhsName','jadwalvhs.batch as jadwalVhsBatch','jadwal_user_vhs.isAllow','jadwal_user_vhs.id')
                    ->get();
        return response()->json([
            'success'=>$data],
        $this->successStatus);
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
        //
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
            $deleted = DB::table('jadwal_user_vhs')
              ->where('id', $id)
              ->delete();
            if ($deleted) {
                return response()->json([
                    'success'=>$deleted],
                $this->successStatus);
            } else {
                return response()->json([
                    'error'=>"error delete, contact administrator"],
                $this->errorStatus);
            }
        } catch (\Exception $th) {
            return response()->json([
                'error'=>$th->getMessage(),],
            $this->errorStatus);
        }          
    }
}
