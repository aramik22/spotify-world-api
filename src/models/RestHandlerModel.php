<?php

namespace Src\Models;

class RestHandlerModel
{

    public function __construct()
    {
    }

    public function setHeaders($authorization = null, $content_type = null, $accept = null)
    {
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
        $client = new \GuzzleHttp\Client(['base_uri' => $url]);
        $request = $client->request($method, $params, [
            'headers' => $headers
        ]);
        $response = $request->getBody();
        var_dump($request);
        return $response;
    }
}
