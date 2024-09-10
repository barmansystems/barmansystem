<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendMessage;


class TicketController extends Controller
{


    public function appSendNotification(Request $request)
    {
//        return $request->all();
        $user = User::whereId($request->user_id)->get();
        $message = $request->message;
        $url = route('tickets.index');
        Notification::send($user, new SendMessage($message, $url));
        return "success";
    }


}
