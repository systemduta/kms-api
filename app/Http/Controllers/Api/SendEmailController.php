<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|array', // Make sure 'email' is an array
                'email.*' => 'email', // Validate each email in the array
                'title' => 'required',
                'message' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => '[' . $validator->errors() . ']',
                    'statusCode' => 500,
                ]);
            }

            $recipients = $request->input('email'); // Access the 'email' input as an array
            $title = $request->input('title');
            $pesan = $request->input('message');

            Mail::send('emails.batch', ['pesan' => $pesan], function ($message) use ($recipients, $title) {
                $message->from('admin-app@maesagroup.co.id', 'Maesa Grow');
                $message->to($recipients)->subject($title);
            });

            return response()->json([
                'message' => 'Data berhasil dikirim!',
                'statusCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan pengiriman [' . $e->getMessage() . ']',
                'statusCode' => 500,
            ]);
        }
    }
}
