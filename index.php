<?php

require_once "vendor/autoload.php";

$router = new \Bramus\Router\Router();
$router->setBasePath('/elections/');

$router->all('elections', function() { require __DIR__ . '/views/elections.php'; });
$router->all('elections/(\w+)', function($electionId) { require __DIR__ . '/views/election.php'; });

$router->all('vote', function() { require __DIR__ . '/views/vote.php'; });
$router->all('preview/(\w+)', function($electionId) { require __DIR__ . '/views/preview.php'; });

$router->get('voterlist/(\w+)', function($vlId) { require __DIR__ . '/views/voterlist.php'; });

$router->run();

exit();
