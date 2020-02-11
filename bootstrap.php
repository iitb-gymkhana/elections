<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Register models

require_once "models/election.php";
require_once "models/post.php";
require_once "models/candidate.php";
require_once "models/vote.php";
require_once "models/voter.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
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

$BASE_URL = '/';
$FULL_URL = 'http://10.105.177.27' . $BASE_URL;
$LOGOUT_HOME = $FULL_URL . 'safe/redir?logout=' . $FULL_URL;

$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader, [
//  'cache' => '/path/to/compilation_cache',
]);

$twig->addGlobal('baseUrl', $BASE_URL);
$twig->addGlobal('sso', 'https://sso-uat.iitb.ac.in');
$twig->addGlobal('ssoredir', $FULL_URL . 'safe/redir?logout=' . $FULL_URL . 'safe/vote');

// User roll number
$USER_ROLL = isset($_SERVER['OIDC_CLAIM_employeeNumber']) ? $_SERVER['OIDC_CLAIM_employeeNumber'] : null;
$USER_SUPERADMIN = in_array($USER_ROLL, array(
    '160010005',
));
$USER_ADMIN = $USER_SUPERADMIN || in_array($USER_ROLL, array(

));

// Stop cache
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
