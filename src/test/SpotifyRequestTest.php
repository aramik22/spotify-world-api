<?php

namespace Src\Test;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;                                           // new line

class SpotifyRequestTest extends TestCase
{

    const APP_NAME = "spotify-world-api";
    const API_SPOTIFY_ENDPOINT = "https://api.spotify.com/";
    const API_SPOTIFY_VERSION = "v1/";
    private $auth;
    const API_CLIENT_CODE = 'MzQzODMwMWJiMzNmNGI3YmFjYjg5NjAxMDRjOWU3ODA6YzIxMmVmYTQwNThlNDZmNmI0MTg1NWVhMzhkMGYwYzY=';
    const API_SPOTIFY_TOKEN_ENDPOINT = "https://accounts.spotify.com/api/token";

    protected function setUp(): void
    {
        parent::setUp();
    }
    public function testGetAccessToken()
    {
        $client = new Client(['base_url' => '']);
        $authorization = 'Basic ';
        $authorization .= self::API_CLIENT_CODE;
        $headers['authorization'] = $authorization;
        $params['grant_type'] = 'client_credentials';
        $response = $client->request("POST", self::API_SPOTIFY_TOKEN_ENDPOINT, [
            'headers' => $headers,
            'form_params' => $params
        ]);
        $statusCode = (int) $response->getStatusCode();
        $data = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('access_token', $data, "Array doesn't contains 'access_token' as key");
    }
    public function testGetBandData()
    {
        $client = new Client(['base_url' => '']);
        $authorization = 'Basic ';
        $authorization .= self::API_CLIENT_CODE;
        $headers['authorization'] = $authorization;
        $params['grant_type'] = 'client_credentials';
        $response = $client->request("POST", self::API_SPOTIFY_TOKEN_ENDPOINT, [
            'headers' => $headers,
            'form_params' => $params
        ]);
        $statusCode = (int) $response->getStatusCode();
        $data = json_decode((string) $response->getBody(), true);
        $authorization = 'Bearer ' . $data['access_token'];
        $headers['authorization'] = $authorization;
        $params = "q=Gustavo Cerati&type=track,artist,album&market=ES";
        $url = self::API_SPOTIFY_ENDPOINT . self::API_SPOTIFY_VERSION . "search?";
        $response = $client->get($url . $params, ["headers" => $headers]);
        $data = json_decode((string) $response->getBody(), true);
        $this->assertArrayHasKey('albums', $data, "Array doesn't contains 'albums' as key");
    }
}
