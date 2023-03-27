<?php
//URUNG inbox ke user
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = DB::table('messages')
                ->join('users', 'users.id', '=', 'messages.user_id')
                ->join('companies','companies.id','=','users.company_id')
                ->join('organizations','organizations.id','=','users.organization_id')
                ->select('messages.*', 'users.name as username','companies.name as comname','organizations.name as orgname')
                ->get();
            return response()->json(
                [
                    'data' => $data,
                    'message' => "get data successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ]
            );
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
            'user_id' => 'required',
            'subject' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $data = Message::create([
                'user_id' => $request->user_id,
                'subject' => $request->subject,
                'content' => $request->content,
                'isSee'  => 0,
            ]);
            
            $tokenUser = DB::table('users')
                ->where('users.id', '=', $request->user_id)
                ->where('token', '!=', "")
                ->pluck('token')
                ->toArray();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
                    ->notification([
                        'title' => 'Hai, ada Pesan baru untuk mu',
                        'body' => 'Silahkan buka menu Message',
                    ])
                    ->data([
                        'title' => 'Hai, ada Pesan baru untuk mu',
                        'body' => 'Silahkan buka menu Message',
                    ])
                    ->send();
            }

            return response()->json(
                [
                    'data' => $data,
                    'message' => "saved successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ]
            );
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
        try {
            $data = Message::findOrFail($id);
            if ($data) {
                return response()->json([
                    'data' => $data,
                    'message' => "geted successfully"
                ]);
            } else {
                return response()->json([
                    'message' => 'cannot find id'
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
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
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
            'subject' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $data = Message::findOrFail($request->id)->update([
                'user_id' => $request->user_id,
                'subject' => $request->subject,
                'content' => $request->content,
                'isSee'   => 0
            ]);
            
            $tokenUser = DB::table('users')
                ->where('users.id', '=', $request->user_id)
                ->where('token', '!=', "")
                ->pluck('token')
                ->toArray();
            if ($tokenUser) {
                $result = fcm()->to($tokenUser)
                    ->timeToLive(0)
                    ->priority('high')
                    ->notification([
                        'title' => 'Hai, ada Pesan baru untuk mu',
                        'body' => 'Silahkan buka menu Message',
                    ])
                    ->data([
                        'title' => 'Hai, ada Pesan baru untuk mu',
                        'body' => 'Silahkan buka menu Message',
                    ])
                    ->send();
            }

            return response()->json(
                [
                    'data' => $data,
                    'message' => "saved successfully"
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ]
            );
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
            $data = Message::findOrFail($id);
            if ($data) {
                Message::destroy($id);

                return response()->json([
                    'message' => 'Success destroy data'
                ]);
            } else {
                return response()->json(
                    [
                        'message' => 'cannot find id'
                    ],403
                );
            }           
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],403
            );
        }
    }
}
