<?php 

namespace Rnsfirebase\Firebase;

 class CloudMessaging 
{
    public $body;
    public $title;
    public $vibrate;
    public $sound;

    private $payload;
    private $server_key;
    private $sender_id;

    public function __construct()
    {
        $this->server_key = config('rnsfirebase.serverkey');
        $this->sender_id = config('rnsfirebase.senderid');
    }

    public function singleTopic($topic)
    {
        $data = array(
            'body'      => $this->body,
            'title'     => $this->title,
        );

        $notif = array(
            'vibrate'   => $this->vibrate,
            'sound'     => $this->sound
        );

        return $this->payload = [
            "to" => "/topics/" . $topic,
            "data" => $data,
            "notification" => $notif,
        ];
    }

    public function multypleTopic(array $topics, $operator)
    {
        $op = [
            'or' => '||',
            'and' => '&&'
        ];
        $data = array(
            'body'      => $this->body,
            'title'     => $this->title,
        );

        $notif = array(
            'vibrate'   => $this->vibrate,
            'sound'     => $this->sound
        );

        $i = 0;
        $condition = "";
        while ($i < count($topics)) {
            $condition = $condition . "'" . $topics[$i] . "' in topics ";
            $end = $i + 1;

            if ($end != count($topics)) {
                $condition = $condition . $op[$operator] . " ";
            }
            $i++;
        }
        return $this->payload = [
            // "to" => "/topics/'jogja.ujione.com_class__' in topics && 'jogja.ujione.com_user_12' in topics",
            // "condition" => "'jogja.ujione.com_class__' in topics && 'jogja.ujione.com_user_12' in topics",
            "condition" => $condition,
            "data" => $data,
            "notification" => $notif,
        ];
    }

    public function send()
    {
        $dataString = json_encode($this->payload);

        $headers = [
            'Authorization: key=' . $this->server_key,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $output = curl_exec($ch);
        return $output;
    }
}
