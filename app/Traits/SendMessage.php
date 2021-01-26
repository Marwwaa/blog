<?php
namespace App\Traits;

trait SendMessage
{
    public function sendMessage($numbers, $msg)
    {
        $url = "https://mshastra.com/sendurl.aspx?";
        $post = ['user' => "20093680", 'pwd' => "MasrySms@123",'senderid' => "SMSAlert", 'mobileno' => $numbers, 'msgtext' => $msg, 'priority' => "High", 'CountryCode' => 'ALL'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $response = curl_exec($ch);
       // dd($response);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }
}
