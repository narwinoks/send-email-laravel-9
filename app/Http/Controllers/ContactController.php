<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);
        if ($this->online()) {
            $mail_data = [
                'recipient' => 'narnowin195@gmail.com',
                'fromEmail' => $request->email,
                'fromName' => $request->name,
                'subject' => $request->subject,
                'body' => $request->message
            ];
            // $test=1;
            Mail::send('email.template_email', $mail_data, function ($message) use ($mail_data) {
                $file=asset('/pdf/contact.pdf');
                $message->to($mail_data['recipient'])
                    ->from($mail_data['fromEmail'], $mail_data['fromName'])
                    ->subject($mail_data['subject'])
                    ->attachData($file, "text.pdf");
                    ;
            });
            return redirect()->back()->with('success', 'Email Send!');
            // return "oke";
        } else {
            return redirect()->back()->withInput()->with('error', 'Check your connection and try again !');
        }
    }

    public function online($site = "https://www.google.com")
    {
        if (@fopen($site, 'r')) {
            return true;
        } else {
            return false;
        }
    }
}
