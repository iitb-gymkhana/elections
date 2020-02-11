<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Register models
require_once "src/election.php";
require_once "src/post.php";
require_once "src/candidate.php";
require_once "src/vote.php";
require_once "src/voter.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'dbname' => 'elections',
    'user' => 'elections',
    'password' => 'n4OZd7Av8XIvBqvS',
    'host' => 'mysql',
    'driver' => 'pdo_mysql',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader, [
//    'cache' => '/path/to/compilation_cache',
]);

// User roll number
$USER_ROLL = $_SERVER['OIDC_CLAIM_employeeNumber'];
$USER_SUPERADMIN = in_array($USER_ROLL, array(
    '160010005',
));
$USER_ADMIN = $USER_SUPERADMIN || in_array($USER_ROLL, array(

));
