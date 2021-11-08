<?php

namespace Src\Controllers;

use Src\Lib\Config;
use Src\Models\AuthModel;

class SpotifyController
{
    private $rest_handler;
    private $auth;
    private $log;
    private $toolkit;
    const API_SPOTIFY_ENDPOINT = "https://api.spotify.com/";
    const API_SPOTIFY_VERSION = "v1/";
    const API_SPOTIFY_DIR = "spotify-world-api";
    const API_SPOTIFY_TOKEN_ENDPOINT = "https://accounts.spotify.com/api/token";
    public function __construct()
    {
        $url = self::API_SPOTIFY_ENDPOINT;
        $this->rest_handler = new RestHandlerController(new \GuzzleHttp\Client(['base_uri' => '']));
        $this->auth = new AuthModel();
        $this->app_name = Config::get('app_name', 'general', true, self::API_SPOTIFY_DIR);
        $this->log = new LogController();
        $this->toolkit = new ToolkitController();
    }
    public function getAccessToken()
    {
        $this->log->logInfo(__FUNCTION__, ' GETTING ACCESS TOKEN');
        $auth_data = $this->auth->where('oauth_name', $this->app_name)->get();
        $authorization = 'Basic ' . base64_encode($auth_data[0]['client_id'] . ':' . $auth_data[0]['client_secret']);
        $headers = $this->rest_handler->setHeaders($authorization);
        $params['grant_type'] = 'client_credentials';
        $response = $this->rest_handler->rest('POST', self::API_SPOTIFY_TOKEN_ENDPOINT, $params, $headers);
        if ($this->toolkit->isValidResponse($response)) {
            return json_encode($response['data'], JSON_PRETTY_PRINT);
        }
    }
    public function getBandData($band)
    {
        try {
            $this->log->logInfo(__FUNCTION__, ' GETTING BAND IN SPOTIFY: ' . $band);
            $access_token = $this->getAccessToken();
            $access_token = json_decode($access_token, true);
            $access_token = $access_token['access_token'];
            $url = self::API_SPOTIFY_ENDPOINT . self::API_SPOTIFY_VERSION . "search?";
            $authorization = 'Bearer ' . $access_token;
            $headers = $this->rest_handler->setHeaders($authorization);
            $params['q'] = $band;
            $params['type'] = Config::get('type', 'spotify', true, self::API_SPOTIFY_DIR);
            $params['market'] = Config::get('market', 'spotify', true, self::API_SPOTIFY_DIR);
            $response = $this->rest_handler->rest('GET', $url, $params, $headers);
            if ($this->toolkit->isValidResponse($response)) {
                $result = $this->formatAlbums($response['data']['albums']['items']);
                $this->log->logInfo(__FUNCTION__, ' RESULT: ' . count($result));
                if (count($result) == 0) {
                    return $this->toolkit->returnMessageWithCode('ARTIST NOT FOUND', 404);
                }
                return $this->processResult(
                    ["data" => $result, "operation" => "successful", "status_code" => $response['status_code']]
                );
            } else {
                return $this->processResult(["operation" => "error", "status_code" => $response['status_code']]);
            }
        } catch (\Throwable $th) {
            $this->log->logError(__FUNCTION__, '', [$th->getMessage()]);
            $this->toolkit->returnMessageWithCode('ERROR', 403);
        }
    }
    public function processResult($result)
    {
        $this->log->logInfo(__FUNCTION__, '');
        if (is_array($result['data']) && $result['status_code'] == 200) {
            $this->log->logInfo(__FUNCTION__, 'SEARCHED SUCCESSFUL');
            header("Content-Type:application/json;charset=UTF-8");
            $result = json_encode($result, JSON_PRETTY_PRINT);
            return $result;
        } else {
            header("HTTP/1.1 {$result['status_code']}  ");
            return json_encode(["operation" => "error", "status_code" => $result['status_code']], JSON_PRETTY_PRINT);
        }
    }
    private function formatAlbum($album)
    {
        $result['name'] = $album['name'];
        $result['released'] = $album['release_date'];
        $result['tracks'] = $album['total_tracks'];
        $result['cover']['height'] = $album['images'][0]['height'];
        $result['cover']['width'] = $album['images'][0]['width'];
        $result['cover']['url'] = $album['images'][0]['url'];
        echo $album['images'][0]['url'];
        var_dump($result);
        return $result;
    }
    private function formatAlbums($albums)
    {
        $this->log->logInfo(__FUNCTION__, '');
        $result = [];
        foreach ($albums as $album) {
            $result[] = $this->formatAlbum($album);
        }
        return $result;
    }
}
