<?php

namespace App\Modules\General\Services;

use App\Modules\Services\Models\ServiceLog;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SMS
{
    /**
     * Gateway Settings
     *
     * @var array
     */
    private $settings = [];

    public $url;
    public $username;
    public $password;
    public $sander_name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->url = config('services.sms.url', "http://www.oursms.net/api/sendsms.php");
        $this->username = config('services.sms.username', 'matasawiq');
        $this->password = config('services.sms.password', 'vd49mwqz6');
        $this->sander_name = config('services.sms.sander_name', 'MUTASWIQ');

        $this->settings = [
            'url' => $this->url,
            'username' => $this->username,
            'password' => $this->password,
            'sander_name' => $this->sander_name,
        ];
    }

    /**
     * Send message to the given numbers
     *
     * @param string $message
     * @param $number
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $message, $number)
    {
        if (!Str::startsWith($number, '+')) {
            if (!Str::startsWith($number, '966')) {
                $number = '966' . $number;
            }
            $number = '+' . $number;
        }

        $request = new Client;

        $message = urlencode($message);

        $fullURl = "{$this->url}?username={$this->username}&password={$this->password}&numbers={$number}&message={$message}&sender={$this->sander_name}&unicode=E&return=json";

//        $response = $request->get($fullURl);

//        $response = json_decode($response->getBody()->getContents());

        $this->log([
            'channel' => 'sendSMS',
            'number' => $number,
            'url' => $fullURl,
//            'response' => $response,
        ]);

        return (isset($response->Code) && $response->Code == "100");
    }

    /**
     * Log the given data
     *
     * @param array $data
     * @return void
     */
    private function log(array $data)
    {
        $data = array_merge($data, [
            'type' => 'sms',
            'gateway' => 'oursms',
            'settings' => $this->settings,
            'userAgent' => request()->userAgent(),
        ]);

        $mapData = function ($data) use (&$mapData) {
            $details = [];

            foreach ($data as $key => $value) {
                $details[Str::camel(str_replace('.', '_', $key))] = is_array($value) || is_object($value) ? $mapData((array) $value) : $value;
            }

            return $details;
        };

        $details = $mapData($data);

        ServiceLog::create($details);
    }
}
