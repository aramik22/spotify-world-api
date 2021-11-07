<?php

namespace Src\Controllers;

use GuzzleHttp\Client as Guzzle;

class RestHandlerController extends Guzzle
{
    private $http;

    public function __construct(Guzzle $http)
    {
        $this->http = $http;
        $this->log = new LogController();
    }
    public function setHeaders($authorization = null, $content_type = null, $accept = null)
    {
        $this->log->logInfo(__FUNCTION__, '');
        if ($authorization) {
            $headers['authorization'] = $authorization;
        }
        if ($content_type) {
            $headers['content_type'] = $content_type;
        }
        if ($accept) {
            $headers['accept'] = $accept;
        }
        return $headers;
    }
    public function rest($method, $url, $params, $headers)
    {
        $this->log->logInfo(__FUNCTION__, '');
        try {
            switch ($method) {
                case 'POST':
                    $response = $this->http->request($method, $url, [
                        'headers' => $headers,
                        'form_params' => $params
                    ]);
                    break;
                case 'GET':
                    $params_get = "";
                    $cant_params = count($params);
                    $i = 1;
                    foreach ($params as $key => $value) {
                        $params_get .= $key . "=" . $value;
                        if ($i < $cant_params) {
                            $params_get .= "&";
                        }
                        $i++;
                    }
                    $response = $this->http->get($url . $params_get, [
                        'headers' => $headers
                    ]);
                    break;
            }
            $statusCode = (int) $response->getStatusCode();
            $data = json_decode((string) $response->getBody(), true);
    
            return [
                "status_code" => $statusCode, "data" => $data
            ];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
