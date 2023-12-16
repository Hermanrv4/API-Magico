<?php
namespace App\Http\Common\Services;

use App\Http\Models\Database\GCMToken;
use App\Http\Models\Database\User;
use Illuminate\Support\Facades\Route;
use Edujugon\PushNotification\PushNotification;

class NotificationService{
    public static function SendNotification($user_id,$title,$description){
        $lstGCMToken = GCMToken::GetByTableId(User::class,$user_id);
        for($i=0;$i<count($lstGCMToken);$i++){
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => [
                    'title' => $title,
                    'body' => $description,
                    'sound' => 'default',
                ],
                'data' => [
                    'extraPayLoad1' => 'value1',
                    'extraPayLoad2' => 'value2',
                ]
            ])->setApiKey('AAAA2-DhlAg:APA91bG6Dit93Lc_eaJZbNEz7hPz-GAEEihmUWp4l-Dlxif30fnrbnYjAEkxJ-rWZqfZDGCLjxJ0O9ZvkLiRQdpEI55lM1DuMNvl0xbM0hyTmYJGjiNmUgqzn-8nCGTkvZAHyfX8wVwa')
            ->setDevicesToken($lstGCMToken[$i]->token)
            ->send();
        }
        return true;
    }
}
