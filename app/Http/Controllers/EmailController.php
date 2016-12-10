<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Mail;
use App\Mail\AccountCreated;
class EmailController extends Controller
{
    /**
     * Send Reminder E-mail Example
     *
     * @return void
     */
    public function createdMail()
    {
        $to_email = 'jchan91913@gmail.com';
        Mail::to($to_email)->send(new AccountCreated);
        return "E-mail has been sent Successfully";
          
    }
}