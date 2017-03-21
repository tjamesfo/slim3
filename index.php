<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello World");

    return $response;
});


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/artists', function(Request $request, Response $response){
    $db = new PDO("sqlite:chinook.db");
    $sql = "SELECT * FROM artists";
    $sth = $db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();
    $db = null;

    return $response->withHeader('Content-type', 'application/json')->withJson($result);
});

$app->get('/artist/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $db = new PDO("sqlite:chinook.db");
    $sql = "SELECT Name FROM artists WHERE ArtistId = :id";
    $sth = $db->prepare($sql);
    $sth->execute([':id' => "$id"]);
    $result = $sth->fetch();
    $db = null;

    return $response->withHeader('Content-type', 'application/json')->withJson($result);
});

$app->run();

