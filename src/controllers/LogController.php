<?php

namespace Src\Controllers;

use Monolog\Logger as Logger;

class LogController extends Logger
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('SPOTIFY-WORLD-API');
        $file_handler = new \Monolog\Handler\StreamHandler("logs/app.log");
        $this->logger->pushHandler($file_handler);
    }
    public function logError($function, $error = '', $arr_data = [])
    {
        return $this->logger->error($function .' :' . $error, $arr_data);
    }
    public function logInfo($function, $info = '', $arr_data = [])
    {
        return $this->logger->info($function .' :' . $info, $arr_data);
    }
}
