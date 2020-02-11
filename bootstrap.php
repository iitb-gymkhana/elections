<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";
require_once "config.php";

// Register models
require_once "models/election.php";
require_once "models/post.php";
require_once "models/candidate.php";
require_once "models/vote.php";
require_once "models/voter.php";

if ($isDevMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$cache = new \Doctrine\Common\Cache\ApcuCache();
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"), $isDevMode, null, $cache, false);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

$LOGOUT_HOME = $FULL_URL . 'safe/redir?logout=' . $FULL_URL;

$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader, [
//  'cache' => '/path/to/compilation_cache',
]);

// Twig globals
$twig->addGlobal('baseUrl', $BASE_URL);
$twig->addGlobal('sso', $SSO_BASE);
$twig->addGlobal('ssoredir', $FULL_URL . 'safe/redir?logout=' . $FULL_URL . 'safe/vote');
$twig->addGlobal('logoutHome', $LOGOUT_HOME);
$twig->addGlobal('year', date("Y"));

// User roll number
$USER_ROLL = isset($_SERVER['OIDC_CLAIM_employeeNumber']) ? $_SERVER['OIDC_CLAIM_employeeNumber'] : null;
$USER_SUPERADMIN = in_array($USER_ROLL, $SUPERADMIN_LIST);
$USER_ADMIN = $USER_SUPERADMIN || in_array($USER_ROLL, $ADMIN_LIST);

// Pass globally
$twig->addGlobal('userRoll', $USER_ROLL);

// Stop cache
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
