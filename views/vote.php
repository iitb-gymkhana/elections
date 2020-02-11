<?php
require_once "bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    exit();
}

$elections = $entityManager->getRepository('Election')->findAll();

echo $twig->render('vote.html', ['elections' => $elections]);
