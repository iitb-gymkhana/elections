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
$isDevMode = false;

// URL config
$BASE_URL = '/election/';
$FULL_URL = 'https://gymkhana.iitb.ac.in' . $BASE_URL;
$SSO_BASE = 'https://sso-uat.iitb.ac.in';

// List of roll numbers who are superadmin
$SUPERADMIN_LIST = array(
    'misc.mlc', '160010005',
);

// List of roll numbers who are admin
$ADMIN_LIST = array(

);

$LDAP_SERVER = 'ldap.iitb.ac.in';
