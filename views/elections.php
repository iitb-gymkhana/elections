<?php
require_once "bootstrap.php";

// Handle creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = new Election();
    $election->setName($_POST['name']);
    $election->setActive(false);
    $election->setEnded(false);

    $entityManager->persist($election);
    $entityManager->flush();

    header("HTTP/1.1 303 See Other");
    header("Location: /elections/" . $election->getId());
    die();
}

$elections = $entityManager->getRepository('Election')->findAll();

echo $twig->render('elections.html', ['elections' => $elections]);
