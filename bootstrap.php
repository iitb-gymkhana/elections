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

$cacheDir = dirname(__FILE__).'/.cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir);
}

$cache = new \Doctrine\Common\Cache\ApcuCache();
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"), $isDevMode, $cacheDir, $cache, false);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);

$LOGOUT_HOME = $FULL_URL . 'safe/redir?logout=' . $FULL_URL;

$loader = new \Twig\Loader\FilesystemLoader('./templates');
$twig = new \Twig\Environment($loader, [
    'cache' => ($isDevMode === true ? false : __DIR__.'/.cache_twig'),
]);

// Uppercase admins list
$SUPERADMIN_LIST = array_map('strtoupper', $SUPERADMIN_LIST);
$ADMIN_LIST = array_map('strtoupper', $ADMIN_LIST);

// User roll number
$USER_ROLL = isset($_SERVER['REDIRECT_OIDC_CLAIM_employeeNumber']) ? strtoupper($_SERVER['REDIRECT_OIDC_CLAIM_employeeNumber']) : null;
$USER_SUPERADMIN = in_array($USER_ROLL, $SUPERADMIN_LIST);
$USER_ADMIN = $USER_SUPERADMIN || in_array($USER_ROLL, $ADMIN_LIST);

// Twig globals
$twig->addGlobal('baseUrl', $BASE_URL);
$twig->addGlobal('sso', $SSO_BASE);
$twig->addGlobal('ssoredir', $FULL_URL . 'safe/redir?logout=' . $FULL_URL . 'safe/vote');
$twig->addGlobal('logoutHome', $LOGOUT_HOME);
$twig->addGlobal('year', date("Y"));
$twig->addGlobal('superAdmin', $USER_SUPERADMIN);
$twig->addGlobal('userRoll', $USER_ROLL);

// Stop cache
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
