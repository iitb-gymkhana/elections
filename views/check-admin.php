<?php
if (!$USER_ADMIN) {
    http_response_code(403);
    echo $twig->render('vote-message.html', [
        'message' => 'You need admin privileges to access this page!',
        'redir' => $LOGOUT_HOME,
    ]);
    die();
}

function dieNoElection() {
    http_response_code(404);
    echo "No such election!";
    die();
}
