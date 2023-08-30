<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\UserScore;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CourseController extends Controller
{
    public $successStatus = 200; //variabel yang akan dipanggil saat proses berhasil dilakukan
    public $errorStatus = 403; //variabel yang akan dipanggil saat proses tidak berhasil dilakukan

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newIndex()
    {
        try {
            $data = DB::table('golongans')
                ->whereNotIn('id', [9, 10, 11, 5, 6, 7])
                ->get();

            return response()->json(['data' => $data], $this->successStatus);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $this->errorStatus);
        }
    }

    public function allcourse($id)
    {
        $auth = auth()->user();
        $cek = DB::table('permissions')->where('user_id', $auth->id)->where('isSuperAdmin', 1)->first();
        try {
            if ($id == 4) {
                $data = DB::table('courses')
                    ->leftJoin('golongans', 'golongans.id', 'courses.golongan_id')
                    ->where('type', 4)
                    ->select('courses.*', 'golongans.name as golongan_name');
                if (!$cek) {
                    $data->where('company_id', $auth->company_id);
                }
                // $data->get();
                return response()->json(['data' => $data->get()], $this->successStatus);
            } elseif ($id == 1) {
                $data = DB::table('courses')
                    ->leftJoin('golongans', 'golongans.id', 'courses.golongan_id')
                    ->where('type', 1)
                    ->select('courses.*', 'golongans.name as golongan_name');
                if (!$cek) {
                    $data->where('company_id', $auth->company_id);
                }
                // $data->get();
                return response()->json(['data' => $data->get()], $this->successStatus);
            } else {
                $data = DB::table('courses')
                    ->leftJoin('golongans', 'golongans.id', 'courses.golongan_id')
                    ->where('type', 2)
                    ->select('courses.*', 'golongans.name as golongan_name');
                if (!$cek) {
                    $data->where('company_id', $auth->company_id);
                }
                // $data->get();
                return response()->json(['data' => $data->get()], $this->successStatus);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], $this->errorStatus);
        }
    }

    //softskill

    public function storesofskill(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'organization_id' => 'numeric|nullable',
            'golongan_id' => 'numeric|nullable',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'video' => 'file|nullable',
            'file' => 'required',
            'link' => 'string|nullable',
            'type' => 'required',
            'questions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $auth = auth()->user();
        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',') + 1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'file_' . Str::random(10) . '.' . $ext;
        Storage::disk('public')->put('files/' . $filename, base64_decode($file));
        /* END FILE UPLOAD */

        /* PREPARE VIDEO UPLOAD */
        $video_name = null;
        $video_path = null;
        $video = null;
        if ($request->hasFile('video')) {
            $video = $request->video;
            $video_path = 'files/course/video/';
            $video_name = Str::random(20) . '.' . $video->getClientOriginalExtension();
        }
        /* END PREPARE VIDEO UPLOAD */
        try {
            DB::beginTransaction();
            $courseGetId = DB::table('courses')->insertGetId([
                'company_id' => $request->company_id ?? null,
                'organization_id' => $request->organization_id ?? null,
                'golongan_id' => $request->golongan_id ?? null,
                'title' => $request->title,
                'description' => $request->description,
                'image' => '',
                'video' => $video_path . $video_name,
                'file' => 'files/' . $filename,
                'link' => $request->link,
                'type' => $request->type
            ]);

            /* START VIDEO UPLOAD */
            if ($request->hasFile('video')) {
                try {
                    Storage::disk('public')->put($video_path . $video_name, file_get_contents($video));
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 401);
                }
            }
            /* END VIDEO UPLOAD */
            $organization_id = $request->organization_id ?? null;
            $tokenUser = DB::table('users')
                // ->when($auth->role!=1, function ($q) use ($auth) {
                //     return $q->where('company_id', $auth->company_id);
                // })
                ->when($organization_id, function ($query) use ($organization_id) {
                    return $query->where('organization_id', $organization_id);
                })
                ->where('token', '!=', "")
                ->pluck('token')->toArray();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
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
            if ($request->filled('image')) {
                $imgName = '';
                $baseString = explode(';base64,', $request->image);
                $image = base64_decode($baseString[1]);
                $image = imagecreatefromstring($image);

                $ext = explode('/', $baseString[0]);
                $ext = $ext[1];
                $imgName = 'course_' . uniqid() . '.' . $ext;
                if ($ext == 'png') {
                    imagepng($image, public_path() . '/files/' . $imgName, 8);
                } else {
                    imagejpeg($image, public_path() . '/files/' . $imgName, 20);
                }
                DB::table('courses')->where('id', $courseGetId)->update(['image' => $imgName]);
            }

            foreach ($request->questions as $question) {
                if (!$question["answers"]) continue;
                $qId = DB::table('test_questions')->insertGetId([
                    'course_id' => $courseGetId,
                    'is_pre_test' => $question['is_pre_test'],
                    'description' => $question['description'],
                ]);
                foreach ($question['answers'] as $answer) {
                    $ans = DB::table('test_answers')->insert([
                        'test_question_id' => $qId,
                        'name' => $answer['name'],
                        'is_true' => $answer['is_true'],
                    ]);
                }
            }
            DB::commit();

            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'User menambahkan course baru (softskill)'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
        return response()->json([
            'data' => $courseGetId,
            'message' => 'Data berhasil disimpan!'
        ], $this->successStatus);
    }

    public function detailssoftskill($id)
    {
        $course = Course::where('id', $id)->first();
        return response()->json(['success' => $course], $this->successStatus);
    }

    public function updatesoftskill(Request $request, $id)
    {
        $title = $request->title;
        $description = $request->description;
        $image = '';
        $video = $request->video;
        $link = $request->link;

        if ($request->has('file')) {
            /* START FILE UPLOAD */
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',') + 1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = 'file_' . Str::random(10) . '.' . $ext;
            Storage::disk('public')->put('files/' . $filename, base64_decode($file));
            /* END FILE UPLOAD */

            $course = Course::find($id);
            $course->file = 'files/' . $filename;
            $course->save();
        }

        if ($request->filled('image')) {
            $imgName = '';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'course_' . uniqid() . '.' . $ext;
            if ($ext == 'png') {
                imagepng($image, public_path() . '/files/' . $imgName, 8);
            } else {
                imagejpeg($image, public_path() . '/files/' . $imgName, 20);
            }
            $course = Course::find($id);
            $course->image = $imgName;
            $course->save();
        }

        $course = Course::find($id);
        $course->company_id = null;
        $course->organization_id = null;
        $course->golongan_id = $request->golongan_id;
        $course->title = $title;
        $course->description = $description;
        $course->video = $video;
        $course->link = $link;
        $course->save();

        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'Melakukan update softskill'
        ]);

        return response()->json(
            [
                'success' => $course,
                'message' => 'update successfully'
            ],
            $this->successStatus
        );
    }

    public function showsoftskill($id)
    {
        $course = Course::where('golongan_id', $id)->where('type', 4)->get();
        return response()->json(['success' => $course], $this->successStatus);
    }

    public function showscore($id)
    {
        try {
            $data = DB::table('user_scores as us')
                ->leftJoin('users as u', 'u.id', '=', 'us.user_id')
                ->leftJoin('courses as c', 'c.id', '=', 'us.course_id')
                ->leftJoin('companies as com', 'com.id', '=', 'u.company_id')
                ->leftJoin('golongans as gol', 'gol.id', '=', 'u.golongan_id')
                ->select('us.id', 'u.name as username', 'com.name as comname', 'gol.name as golname', 'c.title', 'score')
                ->where('c.id', '=', $id)
                ->get();

            return response()->json(['success' => $data], $this->successStatus);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $this->errorStatus);
        }
    }
    //endsoftskill

    //ourcompany



    //end-ourcompany



    /**
     * Pada function coursedown, terdapat sebuah query yang menggunakan fungsi DB::table() untuk mengambil data dari tabel 'courses'. Kemudian, fungsi select() digunakan untuk memilih kolom 'file' yang akan ditampilkan pada hasil query, dan fungsi where() digunakan untuk memfilter data berdasarkan kondisi yang ditentukan. Kemudian, fungsi first() digunakan untuk mengambil satu baris data yang sesuai dengan kondisi tersebut. Setelah itu, nilai dari kolom 'file' tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json().
     */
    public function coursedown($id)
    {
        $course = DB::table('courses')->select('file')->where('id', $id)->first();
        return response()->json(['data' => $course->file]);
    }

    /**
     * Pada function index, terdapat sebuah variabel $auth yang berisi data dari user yang sedang login. Kemudian, terdapat sebuah query yang menggunakan model Course dengan menggunakan fungsi when(). Fungsi when() digunakan untuk mengeksekusi salah satu dari dua perintah yang disediakan sesuai dengan kondisi yang ditentukan di dalamnya. Pada kasus pertama, jika role dari user yang login bukan 1, maka akan dilakukan penambahan kondisi pada query yang menampilkan data dari tabel 'courses' yang memiliki company_id yang sama dengan company_id dari user yang login. Pada kasus kedua, apabila terdapat input request dengan key 'type' yang diisi dan memiliki nilai 3, maka akan dilakukan penambahan kondisi pada query yang menampilkan data dari tabel 'courses' yang memiliki type 3. Apabila tidak memenuhi kondisi tersebut, maka akan dilakukan penambahan kondisi pada query yang menampilkan data dari tabel 'courses' yang memiliki type yang tidak sama dengan 3 dan 1, atau memiliki type 1 dan organization_id yang sama dengan organization_id yang dikirimkan dalam request. Kemudian, data yang telah difilter tersebut diurutkan berdasarkan kolom 'id' dengan urutan descending ('DESC') menggunakan fungsi orderBy(). Kemudian, fungsi get() digunakan untuk mengeksekusi query tersebut. Setelah itu, hasil query tersebut dikembalikan dalam bentuk JSON melalui fungsi response()->json().
     */
    public function index(Request $request)
    {
        $auth = auth()->user();
        $data = Course::query()
            ->when($auth->role != 1, function ($q) use ($auth) {
                return $q->where('company_id', $auth->company_id);
            })
            ->when(($request->filled('type') && $request->type == 3), function (Builder $query) use ($request) {
                return $query->where('type', '=', 3);
            }, function (Builder $builder) use ($request) {
                return $builder->where('type', '!=', 4)
                    ->where('type', '!=', 1)
                    ->where('type', '!=', 3)
                    ->orWhere(function (Builder $query) use ($request) {
                        return $query
                            ->where('organization_id', $request->organization_id);
                    });
            })
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json(['data' => $data]);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Ini adalah sebuah fungsi store() yang digunakan untuk menyimpan data course baru ke dalam database. Fungsi ini menerima request dari client, kemudian melakukan validasi terhadap data yang dikirimkan. Jika validasi berhasil, maka sistem akan menyimpan data course tersebut ke dalam database.
     * Fungsi ini juga akan meng-upload file video jika ada file video yang dikirimkan oleh client. Jika file video sudah ter-upload dengan sukses, maka sistem akan mengirimkan notifikasi kepada user yang terdaftar di sistem dengan menggunakan Firebase Cloud Messaging (FCM).
     * Setelah data disimpan ke dalam database dan notifikasi terkirim kepada user, maka sistem akan mengembalikan respon berupa pesan berhasil disimpan ke client. Jika terjadi error, maka sistem akan mengembalikan respon berupa pesan error ke client.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'organization_id' => 'numeric|nullable',
            'golongan_id' => 'numeric|nullable',
            'company_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'video' => 'file|nullable',
            'file' => 'required',
            'link' => 'string|nullable',
            'type' => 'required',
            'questions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $auth = auth()->user();
        /* START FILE UPLOAD */
        $file64 = $request->file;
        $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
        $replace = substr($file64, 0, strpos($file64, ',') + 1);
        $file = str_replace($replace, '', $file64);
        $file = str_replace(' ', '+', $file);
        $filename = 'file_' . Str::random(10) . '.' . $ext;
        Storage::disk('public')->put('files/' . $filename, base64_decode($file));
        /* END FILE UPLOAD */

        /* PREPARE VIDEO UPLOAD */
        $video_name = null;
        $video_path = null;
        $video = null;
        if ($request->hasFile('video')) {
            $video = $request->video;
            $video_path = 'files/course/video/';
            $video_name = Str::random(20) . '.' . $video->getClientOriginalExtension();
        }
        /* END PREPARE VIDEO UPLOAD */
        try {
            DB::beginTransaction();
            $courseGetId = DB::table('courses')->insertGetId([
                'company_id' => $request->company_id ?? null,
                'organization_id' => $request->organization_id ?? null,
                'golongan_id' => $request->golongan_id ?? null,
                'title' => $request->title,
                'description' => $request->description,
                'image' => '',
                'video' => $video_path . $video_name,
                'file' => 'files/' . $filename,
                'link' => $request->link,
                'type' => $request->type
            ]);

            /* START VIDEO UPLOAD */
            if ($request->hasFile('video')) {
                try {
                    Storage::disk('public')->put($video_path . $video_name, file_get_contents($video));
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 401);
                }
            }
            /* END VIDEO UPLOAD */
            $organization_id = $request->organization_id ?? null;
            $tokenUser = DB::table('users')
                // ->when($auth->role!=1, function ($q) use ($auth) {
                //     return $q->where('company_id', $auth->company_id);
                // })
                ->when($organization_id, function ($query) use ($organization_id) {
                    return $query->where('organization_id', $organization_id);
                })
                ->where('token', '!=', "")
                ->pluck('token')->toArray();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
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
            if ($request->filled('image')) {
                $imgName = '';
                $baseString = explode(';base64,', $request->image);
                $image = base64_decode($baseString[1]);
                $image = imagecreatefromstring($image);

                $ext = explode('/', $baseString[0]);
                $ext = $ext[1];
                $imgName = 'course_' . uniqid() . '.' . $ext;
                if ($ext == 'png') {
                    imagepng($image, public_path() . '/files/' . $imgName, 8);
                } else {
                    imagejpeg($image, public_path() . '/files/' . $imgName, 20);
                }
                DB::table('courses')->where('id', $courseGetId)->update(['image' => $imgName]);
            }

            foreach ($request->questions as $question) {
                if (!$question["answers"]) continue;
                $qId = DB::table('test_questions')->insertGetId([
                    'course_id' => $courseGetId,
                    'is_pre_test' => $question['is_pre_test'],
                    'description' => $question['description'],
                ]);
                foreach ($question['answers'] as $answer) {
                    $ans = DB::table('test_answers')->insert([
                        'test_question_id' => $qId,
                        'name' => $answer['name'],
                        'is_true' => $answer['is_true'],
                    ]);
                }
            }
            DB::commit();
            DB::table('activities')->insert([
                'user_id' => auth()->user()->id,
                'time' => Carbon::now(),
                'details' => 'Menambahkan course baru'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new HttpException(500, $exception->getMessage(), $exception);
        }
        return response()->json([
            'data' => $courseGetId,
            'message' => 'Data berhasil disimpan!'
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

    /**
     * Ini adalah sebuah fungsi detailsCourse() yang digunakan untuk mengambil detail data course berdasarkan ID course yang diberikan. Fungsi ini menerima parameter ID course, kemudian mengambil data course tersebut dari database, dan mengembalikan data tersebut kepada client. Jika data tidak ditemukan, maka sistem akan mengembalikan respon berupa pesan error ke client.
     */
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

    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama update() yang menerima sebuah objek Request dan parameter $id. Fungsi ini digunakan untuk mengupdate data pada sebuah objek Course yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat beberapa variabel yang di-assign dengan nilai dari inputan yang dikirimkan dari objek Request yaitu $title, $description, $image, $video, dan $link. Kemudian ada proses upload file yang dilakukan dengan memisahkan file yang dikirimkan dari objek Request dengan menggunakan base64 ke dalam sebuah variabel $file64, lalu mengekstrak ekstensi file tersebut dengan memecah string tersebut menggunakan explode() lalu menyimpan ekstensi file tersebut pada variabel $ext. Selanjutnya, file tersebut di-replace dengan string kosong dan disimpan pada variabel $file. Kemudian, variabel $file di-replace lagi dengan string kosong dan disimpan pada variabel $filename. File tersebut kemudian disimpan pada direktori 'public/files/' dengan nama file yang sudah disimpan pada variabel $filename.
     * Jika terdapat inputan pada objek Request yang memiliki nama image, maka akan dilakukan proses upload gambar. Proses ini dilakukan dengan memisahkan base64 string dari nama file dengan menggunakan explode(), lalu mengekstrak ekstensi file tersebut dengan cara yang sama seperti proses upload file tadi. Kemudian, file tersebut didecode dengan base64_decode() dan disimpan pada variabel $image. Selanjutnya, file tersebut diconvert ke dalam bentuk gambar dengan menggunakan imagecreatefromstring(). Kemudian, file tersebut akan disimpan pada direktori 'public/files/' dengan nama yang sudah disimpan pada variabel $imgName.
     * Setelah proses upload file dan gambar selesai, objek Course yang memiliki id yang sama dengan parameter $id akan di-update dengan nilai yang telah disimpan pada variabel-variabel di atas. Kemudian, objek tersebut disimpan dengan menggunakan method save(). Terakhir, fungsi akan mengembalikan sebuah objek JSON yang berisi informasi tentang objek Course yang telah diupdate dan pesan 'update successfully'.
     */
    public function update(Request $request, $id)
    {
        $title = $request->title;
        $description = $request->description;
        $image = '';
        $video = $request->video;
        $link = $request->link;

        if ($request->has('file')) {
            /* START FILE UPLOAD */
            $file64 = $request->file;
            $ext = explode('/', explode(':', substr($file64, 0, strpos($file64, ';')))[1])[1];
            $replace = substr($file64, 0, strpos($file64, ',') + 1);
            $file = str_replace($replace, '', $file64);
            $file = str_replace(' ', '+', $file);
            $filename = 'file_' . Str::random(10) . '.' . $ext;
            Storage::disk('public')->put('files/' . $filename, base64_decode($file));
            /* END FILE UPLOAD */

            $course = Course::find($id);
            $course->file = 'files/' . $filename;
            $course->save();
        }


        if ($request->filled('image')) {
            $imgName = '';
            $baseString = explode(';base64,', $request->image);
            $image = base64_decode($baseString[1]);
            $image = imagecreatefromstring($image);

            $ext = explode('/', $baseString[0]);
            $ext = $ext[1];
            $imgName = 'course_' . uniqid() . '.' . $ext;
            if ($ext == 'png') {
                imagepng($image, public_path() . '/files/' . $imgName, 8);
            } else {
                imagejpeg($image, public_path() . '/files/' . $imgName, 20);
            }
            $course = Course::find($id);
            $course->image = $imgName;
            $course->save();
        }

        $course = Course::find($id);
        $course->company_id = $request->company_id;
        $course->organization_id = $request->organization_id;
        $course->golongan_id = $request->golongan_id;
        $course->title = $title;
        $course->description = $description;
        $course->video = $video;
        $course->link = $link;
        $course->save();


        // DB::commit();
        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'update course'
        ]);
        return response()->json(
            [
                'success' => $course,
                'message' => 'update successfully'
            ],
            $this->successStatus
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Pada kode di atas, terdapat sebuah fungsi yang bernama destroy() yang menerima sebuah objek Request dan parameter $id. Fungsi ini digunakan untuk menghapus sebuah objek Course yang memiliki id yang sama dengan parameter $id.
     * Pertama, terdapat sebuah query yang mengambil id dari objek Course yang memiliki id yang sama dengan parameter $id dan disimpan pada variabel $qId. Kemudian, terdapat query yang mengambil id dari objek TestQuestion yang memiliki course_id yang sama dengan id dari objek Course yang disimpan pada variabel $qId dan disimpan pada variabel $tq.
     * Selanjutnya, terdapat proses penghapusan data pada beberapa tabel yaitu tabel user_scores, tabel test_answers, tabel test_questions, dan tabel courses. Pada tabel user_scores, data akan dihapus dengan mengambil id yang memiliki course_id yang sama dengan id dari objek Course yang disimpan pada variabel $qId. Pada tabel test_answers, data akan dihapus dengan mengambil id yang memiliki test_question_id yang sama dengan id dari objek TestQuestion yang disimpan pada variabel $tq. Pada tabel test_questions, data akan dihapus dengan mengambil id yang memiliki course_id yang sama dengan id dari objek Course yang disimpan pada variabel $qId. Dan terakhir, data pada tabel courses akan dihapus dengan mengambil id yang sama dengan parameter $id.
     * Setelah proses penghapusan data selesai, fungsi akan mengembalikan sebuah objek JSON yang berisi pesan 'delete successfully'.
     */
    public function destroy(Request $request, $id)
    {
        $qId = DB::table('courses')->where('id', $id)->select('id')->get();
        $tq = DB::table('test_questions')->where('course_id', $qId[0]->id)->select('id')->get();
        // $ta = DB::table('user_scores')->where('course_id',$qId[0]->id)->select('id')->get();
        DB::table('user_scores')->where('course_id', $qId[0]->id)->select('id')->delete();
        DB::table('test_answers')->where('test_question_id', $tq[0]->id)->select('id')->delete();
        DB::table('test_questions')->where('course_id', $qId[0]->id)->select('id')->delete();
        DB::table('courses')->where('id', $id)->select('id')->delete();

        // // return $qId;
        DB::table('activities')->insert([
            'user_id' => auth()->user()->id,
            'time' => Carbon::now(),
            'details' => 'Menghapus Course'
        ]);
        return response()->json(['message' => 'delete successfully']);
    }
}
