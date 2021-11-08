<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Src\Controllers\LogController;
use Src\Controllers\SpotifyController;
use Src\Controllers\ToolkitController;

$app = new \Slim\App;
$app->group('/v1', function () use ($app) {
    $app->get('/albums', function (Request $request, Response $response) {
        $spotify = new SpotifyController();
        $toolkit = new ToolkitController();
        $log = new LogController();
        if (!isset($request->getQueryParams()['q'])) {
            $log->logInfo('INDEX', 'q parameter not provided');
            return "AN ERROR OCURRED";
        }
        $band = $request->getQueryParams()['q'];
        $response = $spotify->getBandData($band);
        return $response;
    });
    $app->get('/token', function (Request $request, Response $response) {
        $spotify = new SpotifyController();
        $toolkit = new ToolkitController();
        $response = $spotify->getAccessToken();
        return $response;
    });
});
