<?php

require_once "vendor/autoload.php";

$router = new \Bramus\Router\Router();

$router->all('elections', function() { require __DIR__ . '/views/elections.php'; });
$router->all('elections/(\w+)', function($electionId) { require __DIR__ . '/views/election.php'; });

$router->run();

exit();
