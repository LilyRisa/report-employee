<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class Requestapi
{
    protected $url;
    protected $http;
    protected $headers;

    public function __construct($url)
    {
        $data =  DB::Table('systemconfig')->select('Server_ip')->where('id',1)->get();
        //  dd($data);
        $this->url = 'http://'.$data[0]->Server_ip.$url;
        $this->http = new Client();
        $this->headers = [
            'cache-control' => 'no-cache',
            'content-type' => 'application/json',
        ];
    }

    public function getResponse(array $param = null)
    {
        $full_path = $this->url;

        $request = $this->http->post($full_path, [
            'headers'         => $this->headers,
            'timeout'         => 30,
            'connect_timeout' => true,
            'http_errors'     => true,
            'body'            => json_encode($param)
        ]);

        $response = $request ? $request->getBody()->getContents() : null;
        $status = $request ? $request->getStatusCode() : 500;

        if ($response && $status === 200 && $response !== 'null') {
            return $response;
        }

        return null;
    }

}