<?php

namespace Src\Lib;



class Config
{
    public static $global_config = null;
    const LOCAL_DIR = 'src/config/';
    const DEFAULT_CONFIG = 'config.conf';
    const GENERAL_CONFIG_SECTION = 'general';

    public function __construct()
    {
    }

    public static function load($filename = null, $api_dir = '')
    {
        $config_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $api_dir . '/' . self::LOCAL_DIR;
        $config_file .= ($filename) ? $filename : self::DEFAULT_CONFIG;
        if (!file_exists($config_file)) {
            $log_msg = "File [" . $config_file . "] does not exists\n";
            exit($log_msg);
        }
        self::$global_config = parse_ini_file($config_file, true);
        if (self::$global_config === false) {
            $log_msg = "It looks like file [" . $config_file . "] is corrupted. I cannot understand it\n";
            exit($log_msg);
        }
    }

    public static function get($key, $section = 'general', $throw_exception = false, $api_dir)
    {
        self::load(self::DEFAULT_CONFIG, $api_dir);
        if (!isset(self::$global_config[$section]) || !isset(self::$global_config[$section][$key])) {
            if ($throw_exception) {
                $log_msg = "Required config key [{$key}] is missing on config section [{$section}]";
                //throw new S1Exception\InternalServerException($log_msg, S1ERR_CONFIG_KEY_NOTFOUND);
            }
            return null;
        }
        return self::$global_config[$section][$key];
    }
}
