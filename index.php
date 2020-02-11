<?php

require_once "vendor/autoload.php";

$router = new \Bramus\Router\Router();

$router->all('/safe/admin', function() { require __DIR__ . '/views/elections.php'; });
$router->all('/safe/admin/(\w+)', function($electionId) { require __DIR__ . '/views/election.php'; });

$router->all('/safe/vote', function() { require __DIR__ . '/views/vote.php'; });
$router->all('/safe/preview/(\w+)', function($electionId) { require __DIR__ . '/views/preview.php'; });
$router->get('/safe/result/(\w+)', function($electionId) { require __DIR__ . '/views/result.php'; });

$router->get('/safe/voterlist/(\w+)', function($vlId) { require __DIR__ . '/views/voterlist.php'; });

$router->all('', function() { require __DIR__ . '/views/index.php'; });

$router->run();

exit();
