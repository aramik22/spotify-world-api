<?php

namespace Src\Controllers;


class ToolkitController
{

    public function __construct()
    {
    }
    public function isValidResponse($response)
    {
        if (isset($response['data'])) {
            return true;
        } else {
            return false;
        }
    }

    public function dd($data)
    {
        echo "<pre>";
        print_r($data);
        exit();
    }
    public function returnMessageWithCode($message, $code)
    {
        header("HTTP/1.1 {$code}  ");
        return json_encode(["operation" => $message, "status_code" => $code], JSON_PRETTY_PRINT);
    }
}
