<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Crossfunction;
use App\Models\JadwalUserVhs;
use App\Models\Lamcross;
use App\Models\Lampiran;
use App\Models\Sop;
use App\Models\UserScore;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vhs_certi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Validator as ValidationValidator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Mail;


class MobileController extends Controller
{
    public $successStatus = 200; // variabel ini akan dipanggil saat operasi sukses dilakukan
    /**

     * Code di bawah merupakan contoh dari sebuah method yang didefinisikan dalam sebuah class pada bahasa pemrograman PHP. Method ini bernama "firebase_token" dan menerima sebuah parameter bernama $request yang merupakan instance dari class Request.

     * Method ini pertama-tama menggunakan Validator::make() untuk memvalidasi input yang diterima melalui parameter $request. Validator::make() akan memeriksa apakah input yang diterima memiliki tipe data string atau nullable. Jika terdapat input yang tidak memenuhi kriteria tersebut, maka akan dikembalikan response dengan pesan error.

     * Setelah itu, method ini mengambil objek user yang sedang login saat ini dengan menggunakan fungsi auth(). Kemudian, method ini mengupdate token pada tabel "users" dengan menggunakan DB::table() dan fungsi where(). Setelah itu, method ini mengembalikan response dengan pesan berhasil update token firebase.

     */

    public function reset_password(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $user = DB::table('users')->where('email', $request->email)->first();
            if (!empty($user)) {
                $this->sendVerificationEmail($user->id);
                return response()->json([
                    'status' => 200,
                    'message' => 'Sukses mendapatkan email',
                    'data' => $user,
                ]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Tidak dapat menemukan email'], 403);
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return response()->json(['status' => 400, 'message' => $msg], 403);
        }
    }

    private function sendVerificationEmail($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $expiryTime = Carbon::now()->addMinutes(30);

        DB::table('users')->where('id',$id)->update(['verification_link_expiry'=>$expiryTime]);

        $verificationLink = route('verify-email', ['token' => $user->username]);

        Mail::send('emails.verify', ['user' => $user, 'verificationLink' => $verificationLink], function ($message) use ($user) {
            $message->from('admin-app@maesagroup.co.id', 'Administrator Maesa Grow');
            $message->to($user->email, 'Admin');
            $message->subject('Verifikasi Email');
        });
    }

    public function verifyEmail($token)
    {
        $user = DB::table('users')->where('username', $token)->first();

        if (!$user) {
            $error = [
                'message'=>'Token Verifikasi tidak valid, silahkan ulangi',
            ];
            return view('emails.error',compact('error'));
        }

        $expiryTime = Carbon::parse($user->verification_link_expiry);
        if ($expiryTime->isPast()) {
            $error = [
                'message'=>'Token Verifikasi telah kadaluarsa, silahkan ulangi',
            ];
            return view('emails.error',compact('error'));
        }

        DB::table('users')->where('username', $token)->update([
            'password' => Hash::make('12345678'),
        ]);

        return view('emails.success', compact('user'));
    }

    public function firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "token" => "string|nullable"
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = \auth()->user();

        DB::table('users')->where('id', $user->id)->update([
            'token' => $request->token
        ]);

        return response()->json([
            'message' => 'update firebase token successfully'
        ], $this->successStatus);
    }

    /**
     * route : 
     *      Route::post('login_mobile', 'MobileController@login_mobile');
     * 
     * fungsi :
     *  untuk login dari user mobile . dengan syarat parameter isWeb != 1
     * 
     * parameter wajib: 
     *  - nik
     *  - password
     *  - isWeb
     */
    public function login_mobile(Request $request)
    {
        if (Auth::attempt([
            'nik' => $request->nik,
            // 'username' => $request->username,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $org = DB::table('organizations')->where('id', $user->organization_id)->first();
            if ($request->isWeb == "1") {
                if ($org->is_str != 1) return response()->json(['message' => 'Unauthorized'], 401);
            }
            $success['company_id'] = $user->company_id;
            $success['organization_id'] = $user->organization_id;
            $success['file'] = $user->file;
            $success['accessToken'] = $user->createToken('nApp')->accessToken;

            DB::table('users')->where('id', $user->id)->update([
                'token' => $request->token
            ]);
            return response()->json($success, $this->successStatus);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    // public function sop_detail($id)
    // {
    //     $data = Sop::findOrFail($id);
    //     return response()->json(['success' => $data]);
    // }

    public function sop_detail($id)
    {
        // $sop_id = $request->sop_id;
        $data = Sop::with(['company', 'organization', 'lampiran', 'crossfunction'])->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    // public function accept_sop(Request $request)
    // {
    //     $sop_id = $request->sop_id;
    //     $sop    = Sop::with(['company','organization','lampiran'])
    //     // $sop    = Sop::with(['company','organization','lampiran'])->query()
    //             ->where('id', $sop_id)
    //             ->first();
    //     if ($sop)
    //         return response()->json(['message' => 'you have taken this SOP'], 401);
    //     $data = Sop::with(['company','organization','lampiran'])->findOrFail($sop_id);
    //     return response()->json([
    //         'data'    => $data,
    //         'message' => 'OK'
    //     ]);
    // }

    public function sop_list()
    {
        $auth = auth()->user();
        $data = Sop::with(['company', 'organization', 'lampiran', 'crossfunction'])
            ->when($auth->role != 1, function ($q) use ($auth) {
                return $q->where('organization_id', $auth->organization_id);
            })
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json(['data' => $data]);
    }

    //    public function lampiran()
    //    {
    //         $auth       = auth()->user();
    //         $lampiran   = Lampiran::with(['company','organization','sop'])
    //                     ->when($auth->role!=1, function ($q) use ($auth) {
    //                         return $q->where('organization_id', $auth->organization_id);
    //                     })
    //                     ->orderBy('id', 'DESC')
    //                     ->get();
    //         return response()->json(['data' => $lampiran]);
    //    }
    //    Sementara ndak dipakai
    //    public function course_list(Request $request)
    //    {
    //        $dt = DB::table('courses as c');
    //        $dt = $dt->leftJoin('user_scores as us','us.course_id','c.id');
    //        $dt = $dt->where('c.organization_id', auth()->user()->organization_id);
    //        $dt = $dt->where('c.type', $request->type);
    //        $dt = $dt->where('us.id','!=', null);
    //        $dt = $dt->selectRaw('
    //            c.id,
    //            c.title,
    //            c.description,
    //            c.image,
    //            if(us.id is not null, 1, 0) as is_going
    //        ')->get();
    //        $data=array();
    //        return response()->json(['data' => $dt]);
    //    }

    public function downFileSop($id)
    {
        $data = Sop::where('id', $id)->first();
        return response()->download(
            public_path(
                'files/' . $data->file,
            ),
            'File SOP'
        );
    }

    public function downFileLampiran($id)
    {
        $data = Lampiran::where('id', $id)->first();
        return response()->download(
            public_path(
                'files/' . $data->file,
            ),
            'File Lampiran'
        );
    }

    public function downFileCross($id)
    {
        $data = Crossfunction::where('id', $id)->first();
        return response()->download(
            public_path(
                'files/' . $data->file,
            ),
            'File SOP'
        );
    }

    public function sop_status($id)
    {
        $data = Sop::where('id', $id)->first();
        // dd($data->title);
        $st_sekarang = $data->status;
        if ($st_sekarang == 1) {
            $sop = Sop::find($id);
            $sop->status = 0;
            $sop->save();
        } else {
            $sop = Sop::find($id);
            $sop->status = 1;
            $sop->save();
        }
        return response()->json(['message' => 'Data Update Successfully'], $this->successStatus);
    }

    public function course_list_dashboard(Request $request)
    {
        $user = auth()->user();
        if ($request->type == 4) {  //softskill
            if ($user->golongan_id == 8 || $user->golongan_id == 9 || $user->golongan_id == 10 || $user->golongan_id == 11) {
                $dt = DB::table('courses as c')
                    ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                    ->where('c.type', 4)
                    ->where(function ($query) {
                        $query->where('c.golongan_id', 8)
                            ->orWhere('c.golongan_id', 9)
                            ->orWhere('c.golongan_id', 10)
                            ->orWhere('c.golongan_id', 11);
                    })
                    ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                    ->orderBy('c.id', 'DESC')
                    ->selectRaw('
                        c.id,
                        c.title,
                        c.description,
                        c.image,
                        count(us.id) as jml,
                        group_concat(us.user_id) as user_list
                    ')
                    ->get();

                $data = array();
                foreach ($dt as $value) {
                    $exclude = explode(",", $value->user_list);
                    if (in_array($user->id, $exclude)) continue;
                    array_push($data, $value);
                }
                return response()->json(['data' => $data]);
            } elseif ($user->golongan_id == 4) {
                $dt = DB::table('courses as c')
                    ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                    ->where('c.type', 4)
                    ->where(function ($query) {
                        $query->where('c.golongan_id', 4);
                    })
                    ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                    ->orderBy('c.id', 'DESC')
                    ->selectRaw('
                        c.id,
                        c.title,
                        c.description,
                        c.image,
                        count(us.id) as jml,
                        group_concat(us.user_id) as user_list
                    ')
                    ->get();

                $data = array();
                foreach ($dt as $value) {
                    $exclude = explode(",", $value->user_list);
                    if (in_array($user->id, $exclude)) continue;
                    array_push($data, $value);
                }
                return response()->json(['data' => $data]);
            } elseif ($user->golongan_id == 3) {
                $dt = DB::table('courses as c')
                    ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                    ->where('c.type', 4)
                    ->where(function ($query) {
                        $query->where('c.golongan_id', 3);
                    })
                    ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                    ->orderBy('c.id', 'DESC')
                    ->selectRaw('
                        c.id,
                        c.title,
                        c.description,
                        c.image,
                        count(us.id) as jml,
                        group_concat(us.user_id) as user_list
                    ')
                    ->get();

                $data = array();
                foreach ($dt as $value) {
                    $exclude = explode(",", $value->user_list);
                    if (in_array($user->id, $exclude)) continue;
                    array_push($data, $value);
                }
                return response()->json(['data' => $data]);
            } elseif ($user->golongan_id == 2) {
                $dt = DB::table('courses as c')
                    ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                    ->where('c.type', 4)
                    ->where(function ($query) {
                        $query->where('c.golongan_id', 2);
                    })
                    ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                    ->orderBy('c.id', 'DESC')
                    ->selectRaw('
                            c.id,
                            c.title,
                            c.description,
                            c.image,
                            count(us.id) as jml,
                            group_concat(us.user_id) as user_list
                        ')
                    ->get();

                $data = array();
                foreach ($dt as $value) {
                    $exclude = explode(",", $value->user_list);
                    if (in_array($user->id, $exclude)) continue;
                    array_push($data, $value);
                }
                return response()->json(['data' => $data]);
            } elseif ($user->golongan_id == 1) {
                $dt = DB::table('courses as c')
                    ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                    ->where('c.type', 4)
                    ->where(function ($query) {
                        $query->where('c.golongan_id', 1);
                    })
                    ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                    ->orderBy('c.id', 'DESC')
                    ->selectRaw('
                        c.id,
                        c.title,
                        c.description,
                        c.image,
                        count(us.id) as jml,
                        group_concat(us.user_id) as user_list
                    ')
                    ->get();

                $data = array();
                foreach ($dt as $value) {
                    $exclude = explode(",", $value->user_list);
                    if (in_array($user->id, $exclude)) continue;
                    array_push($data, $value);
                }
                return response()->json(['data' => $data]);
            } else {
                return response()->json(['error' => 'error'], 403);
            }
        } elseif ($request->type == 1) { //hardskill
            $dt = DB::table('courses as c')
                ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                ->when($user->role != 1 && $request->type != 4, function ($q) use ($user) {
                    return $q->where('c.company_id', $user->company_id);
                })
                ->when($request->type, function ($query) use ($request) {
                    return $query->where('c.type', $request->type);
                })
                ->when($request->type == 1, function ($query) use ($user) {
                    return $query->where('c.organization_id', $user->organization_id)
                        ->where(function ($query) use ($user) {
                            return $query->where('c.golongan_id', $user->golongan_id)->orWhereNull('c.golongan_id');
                        });
                })
                ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                ->orderBy('c.id', 'DESC')
                ->selectRaw('
                c.id,
                c.title,
                c.description,
                c.image,
                count(us.id) as jml,
                group_concat(us.user_id) as user_list
            ')
                ->where('c.type', 1)
                ->get();

            $data = array();
            foreach ($dt as $value) {
                $exclude = explode(",", $value->user_list);
                if (in_array($user->id, $exclude)) continue;
                array_push($data, $value);
            }
            return response()->json(['data' => $data]);
        } elseif ($request->type == 3) {  //corporatevalue
            $dt = DB::table('courses as c')
                ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                ->when($user->role != 1 && $request->type != 4, function ($q) use ($user) {
                    return $q->where('c.company_id', $user->company_id);
                })
                ->when($request->type, function ($query) use ($request) {
                    return $query->where('c.type', $request->type);
                })
                ->when($request->type == 1, function ($query) use ($user) {
                    return $query->where('c.organization_id', $user->organization_id)
                        ->where(function ($query) use ($user) {
                            return $query->where('c.golongan_id', $user->golongan_id)->orWhereNull('c.golongan_id');
                        });
                })
                ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                ->orderBy('c.id', 'DESC')
                ->selectRaw('
                    c.id,
                    c.title,
                    c.description,
                    c.image,
                    count(us.id) as jml,
                    group_concat(us.user_id) as user_list
                ')
                ->where('c.type', 3)
                ->get();

            $data = array();
            foreach ($dt as $value) {
                $exclude = explode(",", $value->user_list);
                if (in_array($user->id, $exclude)) continue;
                array_push($data, $value);
            }
            return response()->json(['data' => $data]);
        } elseif ($request->type == 2) {
            $dt = DB::table('courses as c')
                ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
                // ->when($user->role != 1 && $request->type != 4, function ($q) use ($user) {
                //     return $q->where('c.company_id', $user->company_id);
                //     })
                ->when($request->type, function ($query) use ($request) {
                    return $query->where('c.type', $request->type);
                })
                // ->when($request->type == 1, function ($query) use ($user) {
                //         return $query->where('c.organization_id', $user->organization_id)
                //             ->where(function ($query) use ($user) {
                //                 return $query->where('c.golongan_id', $user->golongan_id)->orWhereNull('c.golongan_id');
                //             });
                //     })
                ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
                ->orderBy('c.id', 'DESC')
                ->selectRaw('
                c.id,
                c.title,
                c.description,
                c.image,
                count(us.id) as jml,
                group_concat(us.user_id) as user_list
                ')
                // ->where('c.golongan_id',$user->golongan_id)
                ->where('c.type', 2)
                ->get();

            $data = array();
            foreach ($dt as $value) {
                $exclude = explode(",", $value->user_list);
                if (in_array($user->id, $exclude)) continue;
                array_push($data, $value);
            }
            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'error'], 403);
        }

        // $user = auth()->user();
        //     $dt = DB::table('courses as c')
        //         ->leftJoin('user_scores as us', 'us.course_id', 'c.id')
        //         ->when($user->role != 1 && $request->type != 4, function ($q) use ($user) {
        //             return $q->where('c.company_id', $user->company_id);
        //             })
        //         ->when($request->type, function ($query) use ($request) {
        //                 return $query->where('c.type', $request->type);
        //             })
        //         ->when($request->type == 1, function ($query) use ($user) {
        //                 return $query->where('c.organization_id', $user->organization_id)
        //                     ->where(function ($query) use ($user) {
        //                         return $query->where('c.golongan_id', $user->golongan_id)->orWhereNull('c.golongan_id');
        //                     });
        //             })
        //         ->groupBy('c.id', 'c.title', 'c.description', 'c.image')
        //         ->orderBy('c.id', 'DESC')
        //         ->selectRaw('
        //         c.id,
        //         c.title,
        //         c.description,
        //         c.image,
        //         count(us.id) as jml,
        //         group_concat(us.user_id) as user_list
        //         ')
        //         ->where('c.golongan_id',$user->golongan_id)
        //         ->get();

        //         $data = array();
        //         foreach ($dt as $value) {
        //             $exclude = explode(",", $value->user_list);
        //             if (in_array($user->id, $exclude)) continue;
        //             array_push($data, $value);
        //         }
        //         return response()->json(['data' => $data]);
    }

    public function list_vhs()
    {
        $type = 3;
        $user = auth()->user();
        $dt = DB::table('courses as c');
        $dt = $dt->leftJoin('user_scores as us', 'us.course_id', 'c.id');
        $dt = $dt->where('c.type', $type);
        $dt = $dt->groupBy('c.id', 'c.title', 'c.description', 'c.image', 'c.video');
        $dt = $dt->orderBy('c.id', 'DESC');
        $dt = $dt->selectRaw('
            c.id,
            c.title,
            c.description,
            c.image as thumbnail,
            c.video,
            count(us.id) as jml,
            group_concat(us.user_id) as user_list
        ')->get();

        $data = array();
        foreach ($dt as $value) {
            $exclude = explode(",", $value->user_list);
            if (in_array($user->id, $exclude)) continue;
            array_push($data, $value);
        }
        return response()->json(['data' => $data]);
    }

    public function covert_question($value)
    {
        return $value->whenNotEmpty(function ($pre) {
            $temp = collect([]);
            $pre->each(function ($item) use (&$temp) {
                $nameA = '';
                $nameB = '';
                $nameC = '';
                $nameD = '';
                $answer = '';

                $item->test_answers->each(function ($it, $key) use (&$nameA, &$nameB, &$nameC, &$nameD, &$answer) {
                    if ($key == 0) $nameA = $it->name;
                    if ($key == 1) $nameB = $it->name;
                    if ($key == 2) $nameC = $it->name;
                    if ($key == 3) $nameD = $it->name;
                    if ($it->is_true == 1) $answer = $it->name;
                });

                $temp->push([
                    'question' => $item->description,
                    'nameA' => $nameA,
                    'nameB' => $nameB,
                    'nameC' => $nameC,
                    'nameD' => $nameD,
                    'answer_true' => $answer,
                ]);
            });
            return $temp;
        });
    }

    public function course_detail($id)
    {
        $ori_data = Course::query()
            ->with(['pre_test_questions.test_answers'])
            ->with(['post_test_questions.test_answers'])
            ->findOrFail($id);
        $pre_test = $this->covert_question($ori_data->pre_test_questions);
        $post_test = $this->covert_question($ori_data->post_test_questions);
        $result = collect([
            'id' => $ori_data->id,
            'title' => $ori_data->title,
            'organization_id' => $ori_data->organization_id,
            'description' => $ori_data->description,
            'image' => $ori_data->image,
            'file' => $ori_data->file,
            'video' => $ori_data->video,
            'link' => $ori_data->link,
            'type' => $ori_data->type,
            'created_at' => $ori_data->created_at,
            'updated_at' => $ori_data->updated_at,
            'pre_test' => $pre_test,
            'test' => $post_test,
        ]);
        return response()->json(['data' => $result]);
    }

    public function user_course(Request $request)
    {
        $dt = DB::table('user_scores as us');
        $dt = $dt->leftJoin('courses as c', 'c.id', 'us.course_id');
        $dt = $dt->where('us.user_id', auth()->id());
        $dt = $dt->orderBy('us.id', 'DESC');
        $dt = $dt->selectRaw('
            us.id,
            us.course_id,
            us.score,
            us.status,
            c.image,
            c.title,
            c.description
        ')->get();
        //        start of formating response
        $data = collect();
        $temp_id = null;
        $temp_course_id = null;
        $temp_score = null;
        $temp_status = null;
        $dt->each(function ($item) use (&$temp_id, &$temp_course_id, &$data, &$temp_score, &$temp_status) {
            if (($item->id == $temp_id - 1) && ($item->course_id == $temp_course_id)) {
                $modifiedElement = array_merge($data[($data->count()) - 1], [
                    "score" => $temp_score + $item->score,
                    "pre_score" => $item->score,
                    "post_score" => $temp_score,
                    "status" => ($item->status == 2 && $temp_status == 2) ? "2" : "1",
                    "pre_status" => $item->status,
                    "post_status" => $temp_status,
                ]);
                // dd($modifiedElement);
                $data->put((($data->count()) - 1), $modifiedElement);
            } else {
                $data->push([
                    //                    "id" => $item->id,
                    "id" => $item->course_id,
                    "course_id" => $item->course_id,
                    "image" => $item->image,
                    "title" => $item->title,
                    "description" => $item->description,
                    "score" => $item->score,
                    "status" => $item->status,
                    "pre_score" => null,
                    "post_score" => null,
                    "pre_status" => null,
                    "post_status" => null,
                ]);
            }

            $temp_id = $item->id;
            $temp_course_id = $item->course_id;
            $temp_score = $item->score;
            $temp_status = $item->status;
        });
        //        end of formating response
        return response()->json(['data' => $data]);
    }

    public function accept_course(Request $request)
    {
        $course_id = $request->course_id;
        $user_score = UserScore::query()
            ->where('course_id', $course_id)
            ->where('user_id', auth()->id())
            ->first();
        if ($user_score)
            return response()->json(['message' => 'you have taken this course'], 401);
        $course = Course::query()->findOrFail($course_id);
        $is_corp_value = $course->type == 3;
        UserScore::query()->when($is_corp_value, function ($query) use ($course_id) {
            return $query->insert([
                [
                    'course_id' => $course_id,
                    'user_id' => auth()->id(),
                    'score' => 0,
                    'status' => 1,
                    'is_pre_test' => 1
                ],
                [
                    'course_id' => $course_id,
                    'user_id' => auth()->id(),
                    'score' => 0,
                    'status' => 1,
                    'is_pre_test' => 0
                ]
            ]);
        }, function ($query) use ($course_id) {
            return $query->insert([
                'course_id' => $course_id,
                'user_id' => auth()->id(),
                'score' => 0,
                'status' => 1,
            ]);
        });

        return response()->json(['message' => 'OK']);
    }

    public function submit_question(Request $request)
    {
        $user = auth()->user();
        DB::table('user_scores')->updateOrInsert([
            'course_id' => $request->course_id,
            'user_id' => $user->id,
        ], [
            'score' => $request->score,
            'status' => 2
        ]);
        return response()->json(['message' => 'OK']);
    }

    public function submit_answer(Request $request)
    {
        DB::beginTransaction();
        $user = auth()->user();
        $params = array();
        if ($request->filled('pre_test') && $request->pre_test) {
            $params = [
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'is_pre_test' => 1
            ];
        } else {
            $params = [
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'is_pre_test' => 0
            ];
        }

        DB::table('user_scores')->updateOrInsert($params, [
            'score' => $request->score,
            'status' => 2
        ]);

        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function leaderboards()
    {
        $user = auth()->user();
        $dt = DB::table('user_scores as us');
        $dt = $dt->leftJoin('users as u', 'u.id', 'us.user_id');
        $dt = $dt->when($user->role != 1, function ($q) use ($user) {
            return $q->where('u.company_id', $user->company_id);
        });
        $dt = $dt->where('u.golongan_id', $user->golongan_id);
        $dt = $dt->groupByRaw('us.user_id,u.name,u.image');
        $dt = $dt->selectRaw('
            u.name,
            u.image,
            sum(us.score) as total_score
        ');

        $dt = $dt->orderBy('total_score', 'desc')->get();
        //        add ui avatar
        $data = collect([]);
        foreach ($dt as $d) {
            $data->push([
                'name' => $d->name,
                'image' => $d->image,
                'ui_avatar' => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . $d->name,
                'total_score' => $d->total_score,
            ]);
        }

        return response()->json(['data' => $data]);
    }

    public function post_calendar(Request $request)
    {
        DB::beginTransaction();
        DB::table('calendars')->insert([
            'user_id' => auth()->id(),
            'date' => Carbon::parse($request->date),
            'description' => $request->description
        ]);
        DB::commit();
        return response()->json(['message' => 'OK']);
    }

    public function get_calendar()
    {
        $dt = DB::table('calendars')->where('user_id', auth()->id())->selectRaw('date, description')->get();
        return response()->json(['data' => $dt]);
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json(['message' => 'Logout Success']);
    }

    public function details()
    {
        $user = User::with('company', 'organization')
            ->where('id', Auth::user()->id)
            ->get();
        return response()->json([
            'message' => 'success',
            'data' => $user
        ], $this->successStatus);
    }

    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
    
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ];
    
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first(), 'data' => []]);
        }
    
        try {
            $user = User::find($userid);
    
            if (!Hash::check($input['old_password'], $user->password)) {
                return response()->json(['status' => 400, 'message' => 'Check your old password.', 'data' => []]);
            }
    
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($input['new_password']),
            ]);
    
            $cekEmail = DB::table('users')->where('email', $request->email)->where('id', '!=', $userid)->count();
    
            if ($cekEmail) {
                return response()->json(['status' => 200, 'message' => 'Sukses update password dan Email sudah terdaftar di user lain', 'data' => []]);
            } else {
                $user->update([
                    'email' => $request->email,]);
                return response()->json(['status' => 200, 'message' => 'Updated successfully.', 'data' => $user]);
            }
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return response()->json(['status' => 400, 'message' => $msg, 'data' => []]);
        }
    }

    public function getVhs(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        $type = $request->name;
        try {
            if ($type == '1VHS Basic') {
                // $datenow = Carbon::now()->toDateString();
                $data = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                    ->where('jadwalvhs.type', $type)
                    ->where('jadwal_user_vhs.user_id', $userid)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    // ->where('jadwalvhs.start','<=',$datenow)
                    // ->where('jadwalvhs.end','>=',$datenow)
                    ->where('jadwal_user_vhs.is_take', '==', '0')
                    ->select('jadwalvhs.*', 'jadwal_user_vhs.id as jadwal_user_id',)
                    ->get();

                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $data,
                    ],
                    200
                );
            } elseif ($type == '1VHS Class') {
                // $datenow = Carbon::now()->toDateString();
                $data = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                    ->where('jadwalvhs.type', $type)
                    ->where('jadwal_user_vhs.user_id', $userid)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    // ->where('jadwalvhs.start','<=',$datenow)
                    // ->where('jadwalvhs.end','>=',$datenow)
                    ->where('jadwal_user_vhs.is_take', '==', '0')
                    ->select('jadwalvhs.*', 'jadwal_user_vhs.id as jadwal_user_id',)
                    ->get();

                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $data,
                    ],
                    200
                );
            } elseif ($type == '1VHS Camp') {
                // $datenow = Carbon::now()->toDateString();
                $data = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                    ->where('jadwalvhs.type', $type)
                    ->where('jadwal_user_vhs.user_id', $userid)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    // ->where('jadwalvhs.start','<=',$datenow)
                    // ->where('jadwalvhs.end','>=',$datenow)
                    ->where('jadwal_user_vhs.is_take', '==', '0')
                    ->select('jadwalvhs.*', 'jadwal_user_vhs.id as jadwal_user_id',)
                    ->get();

                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $data,
                    ],
                    200
                );
            } elseif ($type == '1VHS Academy') {
                // $datenow = Carbon::now()->toDateString();
                $data = DB::table('jadwalvhs')
                    ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                    ->where('jadwalvhs.type', $type)
                    ->where('jadwal_user_vhs.user_id', $userid)
                    ->where('jadwal_user_vhs.isAllow', 1)
                    // ->where('jadwalvhs.start','<=',$datenow)
                    // ->where('jadwalvhs.end','>=',$datenow)
                    ->where('jadwal_user_vhs.is_take', '==', '0')
                    ->select('jadwalvhs.*', 'jadwal_user_vhs.id as jadwal_user_id',)
                    ->get();

                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $data,
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'message' => 'success',
                        'success' => 'error reading type',
                    ],
                    403
                );
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function confirmPickUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_take'               => 'required',
            'jadwal_user_id'        => 'required',
            'jadwal_id'             => 'required',
            'type'                  => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $type = $request->type;
        try {
            if ($type == '1VHS Basic') {
                $JadwalGetId = JadwalUserVhs::where('id', $request->jadwal_user_id)
                    ->update([
                        'is_take'  => $request->is_take,
                    ]);
                if ($JadwalGetId) {
                    $data = DB::table('materi_vhs')
                        ->where('materi_vhs.jadwal_id', '=', $request->jadwal_id)
                        ->get();
                    return response()->json(
                        [
                            'message' => 'success',
                            'success' => $data,
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'error' => 'jadwal_user_id not found on table jadwal_user_vhs',
                        ],
                        403
                    );
                }
            } elseif ($type == '1VHS Class') {
                $JadwalGetId = JadwalUserVhs::where('id', $request->jadwal_user_id)
                    ->update([
                        'is_take'  => $request->is_take,
                    ]);
                if ($JadwalGetId) {
                    $data = DB::table('materi_vhs')
                        ->where('materi_vhs.jadwal_id', '=', $request->jadwal_id)
                        ->get();
                    return response()->json(
                        [
                            'message' => 'success',
                            'success' => $data,
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'error' => 'jadwal_user_id not found on table jadwal_user_vhs',
                        ],
                        403
                    );
                }
            } elseif ($type == '1VHS Camp') {
                $JadwalGetId = JadwalUserVhs::where('id', $request->jadwal_user_id)
                    ->update([
                        'is_take'  => $request->is_take,
                    ]);
                if ($JadwalGetId) {
                    $data = DB::table('materi_vhs')
                        ->where('materi_vhs.jadwal_id', '=', $request->jadwal_id)
                        ->get();
                    return response()->json(
                        [
                            'message' => 'success',
                            'success' => $data,
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'error' => 'jadwal_user_id not found on table jadwal_user_vhs',
                        ],
                        403
                    );
                }
            } elseif ($type == '1VHS Academy') {
                $JadwalGetId = JadwalUserVhs::where('id', $request->jadwal_user_id)
                    ->update([
                        'is_take'  => $request->is_take,
                    ]);
                if ($JadwalGetId) {
                    $data = DB::table('materi_vhs')
                        ->where('materi_vhs.jadwal_id', '=', $request->jadwal_id)
                        ->get();
                    return response()->json(
                        [
                            'message' => 'success',
                            'success' => $data,
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'error' => 'jadwal_user_id not found on table jadwal_user_vhs',
                        ],
                        403
                    );
                }
            } else {
                return response()->json(
                    [
                        'error' => 'type not recognized',
                    ],
                    403
                );
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function getMateri(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id'              => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = DB::table('materi_vhs')
                ->where('materi_vhs.jadwal_id', '=', $request->jadwal_id)
                ->get();
            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function getVhsDetail(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        $type = $request->name;
        $idmateri = $request->idmateri;
        try {
            $data = DB::table('jadwalvhs')
                ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->join('users', 'users.id', '=', 'jadwal_user_vhs.user_id')
                ->join('materi_vhs', 'materi_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->leftJoin('zooms_vhs', 'zooms_vhs.jadwal_id', '=', 'materi_vhs.jadwal_id')
                ->where('users.id', '=', $userid)
                ->where('jadwalvhs.type', '=', $type)
                ->where('materi_vhs.id', '=', $idmateri)
                ->select('jadwal_user_vhs.id as jadwaluservhsId', 'materi_vhs.*', 'zooms_vhs.id as idZoom', 'zooms_vhs.name as nameZoom', 'zooms_vhs.times as timesZoom', 'zooms_vhs.link as linkZoom', 'zooms_vhs.meeting_id as meetingidZoom', 'zooms_vhs.password as passwordZoom')
                ->get();

            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function getVhsQuestion(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        $idmateri = $request->idmateri;
        try {
            $data = DB::table('jadwalvhs')
                ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->join('users', 'users.id', '=', 'jadwal_user_vhs.user_id')
                ->join('materi_vhs', 'materi_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->join('question_vhs', 'question_vhs.materi_id', '=', 'materi_vhs.id')
                ->leftJoin('zooms_vhs', 'zooms_vhs.jadwal_id', '=', 'materi_vhs.jadwal_id')
                ->where('users.id', '=', $userid)
                ->where('materi_vhs.id', '=', $idmateri)
                ->select('question_vhs.*', 'materi_vhs.name as namaMateri', 'jadwal_user_vhs.id as idJadwalUserVhs', 'materi_vhs.isPreTest')
                ->get();

            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function getVhsQuestionDetail(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        $idQuestion = $request->idQuestion;
        try {
            $data = DB::table('jadwalvhs')
                ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->join('users', 'users.id', '=', 'jadwal_user_vhs.user_id')
                ->join('materi_vhs', 'materi_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->join('question_vhs', 'question_vhs.materi_id', '=', 'materi_vhs.id')
                ->leftJoin('zooms_vhs', 'zooms_vhs.jadwal_id', '=', 'materi_vhs.jadwal_id')
                ->where('users.id', '=', $userid)
                ->where('question_vhs.id', '=', $idQuestion)
                ->select('question_vhs.*', 'materi_vhs.name as namaMateri', 'jadwal_user_vhs.id as idJadwalUserVhs', 'materi_vhs.isPreTest')
                ->first();

            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    public function setAnswerVhs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isPreTest'     => 'required',
            'materi_id'     => 'required',
            'question_id'   => 'required',
            'idJadwalUser'  => 'required',
            'is_take'       => 'required',
            'answer'        => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }
        try {
            $userid = Auth::guard('api')->user()->id;
            $cekAnswer = DB::table('answer_vhs')
                ->where('materi_id', $request->materi_id)
                ->where('question_id', $request->question_id)
                ->where('user_id', $userid)
                ->count();
            if ($cekAnswer > 0) {
                return response()->json(
                    [
                        'message' => "Data Double",
                    ],
                    403
                );
            } else {
                $JadwalGetId = JadwalUserVhs::findOrfail($request->idJadwalUser)->update([
                    'is_take'           => $request->is_take,
                ]);
                DB::beginTransaction();
                $answerGetId = DB::table('answer_vhs')->insertGetId([
                    'materi_id'         => $request->materi_id,
                    'question_id'       => $request->question_id,
                    'user_id'           => $userid,
                    'answer'            => $request->answer,
                ]);
                DB::commit();

                if ($request->hasFile('file') && $request->file('file')->isValid()) {
                    $fileEXT    = $request->file('file')->getClientOriginalName();
                    $filename   = pathinfo($fileEXT, PATHINFO_FILENAME);
                    $EXT        = $request->file('file')->getClientOriginalExtension();
                    $fileimage = $filename . '_' . time() . '.' . $EXT;
                    $path       = $request->file('file')->move(public_path('file/answervhs'), $fileimage);

                    DB::table('answer_vhs')->where('id', $answerGetId)->update([
                        'file' => $fileimage
                    ]);
                }

                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $answerGetId,
                    ],
                    200
                );
            }
        } catch (\Exception $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                403
            );
        }
    }

    public function getOtherAnswers(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        $cekAnswer = DB::table('answer_vhs')
            ->join('users', 'users.id', '=', 'answer_vhs.user_id')
            ->join('materi_vhs', 'materi_vhs.id', '=', 'answer_vhs.materi_id')
            ->join('question_vhs', 'question_vhs.id', '=', 'answer_vhs.question_id')
            ->where('answer_vhs.materi_id', $request->materi_id)
            ->where('answer_vhs.question_id', $request->question_id)
            ->where('answer_vhs.user_id', $userid)
            ->select('users.id', 'users.name', 'answer_vhs.answer', 'answer_vhs.file')
            ->count();

        if ($cekAnswer > 0) {
            try {
                $data = DB::table('answer_vhs')
                    ->join('users', 'users.id', '=', 'answer_vhs.user_id')
                    ->join('materi_vhs', 'materi_vhs.id', '=', 'answer_vhs.materi_id')
                    ->join('question_vhs', 'question_vhs.id', '=', 'answer_vhs.question_id')
                    ->where('answer_vhs.materi_id', $request->materi_id)
                    ->where('answer_vhs.question_id', $request->question_id)
                    ->select('users.id', 'users.name', 'answer_vhs.answer', 'answer_vhs.file')
                    ->get();
                return response()->json(
                    [
                        'message' => 'success',
                        'success' => $data,
                    ],
                    200
                );
            } catch (\Exception $exception) {
                DB::rollBack();
                throw new HttpException(500, $exception->getMessage(), $exception);
            }
        } else {
            return response()->json(
                [
                    'message' => 'anda belum menjawab, pastikan sudah menjawab',
                ],
                403
            );
        }
    }

    //PendingAll
    public function getVhsPending(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;
        try {
            $data = DB::table('jadwalvhs')
                ->join('jadwal_user_vhs', 'jadwal_user_vhs.jadwal_id', '=', 'jadwalvhs.id')
                ->select('jadwalvhs.*', 'jadwal_user_vhs.id as jadwal_user_id', 'jadwal_user_vhs.is_take')
                ->where('jadwal_user_vhs.user_id', '=', $userid)
                ->where(function ($query) {
                    $query->where('jadwal_user_vhs.is_take', '=', 1)
                        ->orWhere('jadwal_user_vhs.is_take', '=', 2);
                })
                ->get();

            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    //sertif
    public function getSerti()
    {
        try {
            $user = auth()->user();
            $data = Vhs_certi::where('user_id', $user->id)->get();

            return response()->json(
                [
                    'message' => 'success',
                    'success' => $data,
                ],
                200
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
    }

    //  public function fpassword(Request $request){
    //     try {
    //         //code...
    //     } catch (\Exception $e) {

    //     }
    //  }
}
