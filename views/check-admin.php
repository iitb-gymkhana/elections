<?php
if (!$USER_ADMIN) {
    header('HTTP/1.0 403 Forbidden');
    echo "You need admin privileges to access this page";
    die();
}

function dieNoElection() {
    header('HTTP/1.0 404 Not Found');
    echo "No such election!";
    die();
}
