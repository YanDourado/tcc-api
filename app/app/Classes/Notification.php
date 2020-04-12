<?php

namespace App\Classes;

use App\TCC;

class Notification
{

    protected $url = 'https://exp.host/--/api/v2/push/send';

    protected $channel;

    /**
     * Create the Notification
     *
     * @return void
     */
    public function __construct(string $channel = 'default')
    {
        $this->channel = $channel;
    }

    /**
     * Send the Notification
     *
     * @return boolean
     */
    public function send(string $title, string $body)
    {
        try
        {       
            $params = array('to' => 'ExponentPushToken[BqTazjB-x9BtMp3xxjd1pB]',
                            'title' => $title,
                            'body' => $body,
                            'channelId' => $this->channel);


            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Accept-Encoding: gzip, deflate",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

			$response = curl_exec($ch);
            curl_close($ch);
            
            return true;
        }
        catch (\Exception $e)
        {
            TCC::logError(__FILE__, __LINE__, __METHOD__, $e);

            return false;
        }
    }
}