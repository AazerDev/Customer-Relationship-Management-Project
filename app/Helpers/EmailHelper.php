<?php

namespace App\Helpers;

use App\Models\Meeting;
use App\Models\Client;
use App\Mail\MeetingNotification;
use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    public static function sendMeetingNotification(Meeting $meeting)
    {
        $participants = Client::whereIn('id', $meeting->participants)->get();

        foreach ($participants as $customer) {
            if ($customer->email) {
                Mail::to($customer->email)->queue(new MeetingNotification($meeting));
            }
        }
    }
}