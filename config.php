<?php

// database configuration parameters
$conn = array(
    'dbname' => 'elections',
    'user' => 'elections',
    'password' => 'n4OZd7Av8XIvBqvS',
    'host' => 'mysql',
    'driver' => 'pdo_mysql',
);

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;

// URL config
$BASE_URL = '/';
$FULL_URL = 'http://10.105.177.27' . $BASE_URL;
$SSO_BASE = 'https://sso-uat.iitb.ac.in';

$SUPERADMIN_LIST = array(
    'misc.mlc', '160010005',
);

$ADMIN_LIST = array(

);
