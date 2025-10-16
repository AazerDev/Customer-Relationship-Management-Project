<?php

use App\Models\EmailApi;
use App\Models\Notify;
use Illuminate\Support\Facades\Auth;


function content_match($content , $data, $admin=null) {
        
    preg_match_all( '~{([^{]+)}~', $content, $matches);
    $matchess = array();
    $matchess = $matches[1];
    foreach($matchess as $match => $v){
        if(is_string($v)){
            $match = explode(":",$v);
            if ($match[0] == 'firstname'){
                $check = $data['name'];
            }
            elseif($match[0] == 'lastname'){
                $check = '';
            }
            elseif($match[0] == 'email'){
                $check = $data['email'];
            }
            else{
                $check = "";
            }
            $aa = '{'.$v.'}';
            $content = str_replace($aa, $check, $content);
        }
    }
    return $content; 
}

if (!function_exists('sendMailCurl')) {
    function sendMailCurl($email , $subject , $message , $receiver_name , $sender_name , $sender_email){
        if(empty($receiver_name)){
            $receiver_name = 'dummy';
        }
        $messages = array('sender' => array('name' => $sender_name , 'email' => $sender_email) , 'to' => array(array('email' => $email , 'name' => $receiver_name)) , 'subject' => $subject , 'htmlContent' => $message );
        $messages = json_encode($messages);  
        $api = EmailApi::first();
        if(!empty($api)){
            if(!empty($api->email_api)){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: '.$api->email_api,
            'content-type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messages);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return 1;
            }
        }
    
    }
}


if (!function_exists('sendNotification')) {
    /**
     * Send a notification
     */
    function sendNotification($receiverId, $title, $message, $senderId = null, $orderId = null)
    {
        try {
            return Notify::create([
                'sender_id'   => $senderId ?? (Auth::check() ? Auth::id() : null),
                'receiver_id' => $receiverId,
                'title'       => $title,
                'message'     => $message,
                'is_active'   => 1,
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('getNotifications')) {
    /**
     * Get all active notifications for a user
     */
    function getNotifications($userId = null)
    {
        try {
            $userId = $userId ?? (Auth::check() ? Auth::id() : null);
            return Notify::where('receiver_id', $userId)
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}

if (!function_exists('markNotificationAsRead')) {
    /**
     * Mark a single notification as read
     */
    function markNotificationAsRead($notificationId)
    {
        $notification = Notify::find($notificationId);

        if (!$notification) {
            return false;
        }

        try {
            $notification->update(['is_active' => 0]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

