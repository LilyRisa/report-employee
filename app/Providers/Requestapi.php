<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Config;

class Requestapi
{
    protected $url;
    protected $http;
    protected $headers;

    public function __construct($url)
    {
        $this->url = config('app.SERVER_IP').$url;
        //dd($this->url);
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

    public function methodGet(array $param= null){
        $full_path = $this->url;
        $request = $this->http->get($full_path,[
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