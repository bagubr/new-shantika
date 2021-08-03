<?php 

namespace App\Utils;

class NotificationBody {
    public function __construct(
        public string $title,
        public string $body
    ) {}
}

class Firebase {
    public static function sendNotification(NotificationBody|array $notification, $fcm_token) {
        $token = self::oAuthFirebase()['access_token'];
        $data = json_encode([
            'message'=> (object) [
                "token"=> $fcm_token,
                "notification"=> is_array($notification) ? (object) $notification : $notification
            ]
        ]);

        $ch = curl_init();

        $bearer = "Authorization:Bearer ".$token;
        curl_setopt($ch, CURLOPT_URL,"https://fcm.googleapis.com/v1/projects/agen-santika/messages:send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            $bearer,
            'Content-Type:application/json',
            'Accept:application/json',
            'Content-Length:'.strlen($data),
        ]);
                    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close ($ch);

        return json_decode($output);
    }

    private static function oAuthFirebase() {
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.base_path('agen-santika-firebase-adminsdk-30qo7-3c8d02dbf7.json'));
        $scope = 'https://www.googleapis.com/auth/firebase.messaging';
        $client = new \Google\Client();
        $client->setScopes($scope);
        $client->useApplicationDefaultCredentials();
        
        return $client->fetchAccessTokenWithAssertion();
    }
}