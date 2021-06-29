<?php


namespace App\Events;


class OneSignal
{

    function __construct()
    {
        $options = [
            'base_uri'      => base_url(), // need returned error: 403 Forbidden
            'timeout'       => 1000,
            'http_errors'   => false, // This avoids an exception thrown if the HTTP code is is greater than or equal to 400
        ];
        $this->clients = \Config\Services::curlrequest($options);
    }

    private string $app_id  = APP_ID;

    public string $player_id;

    public array $data;

    public array $content;

    public string $api = ONESIGNAL_API;

    public function send()
    {
        $body = [
            'app_id'                => $this->app_id,
            'include_player_ids'    => $this->player_id,
            'data'                  => $this->data,
            'contents'              => $this->content
        ];

        $encoded = $this->clients->setJSON($body);

        $response = $this->clients->setBody($encoded)->request('POST', 'https://onesignal.com/api/v1/notifications',
            [
                'headers' => [
                    'Content-type'       => 'application/json'
                ]
            ]);

        $formatted['allresponses'] =  json_decode($response->getBody(),true);

        $return = json_encode($formatted);


        return true;

    }


}