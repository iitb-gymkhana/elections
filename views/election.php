<?php
require_once "bootstrap.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) { echo "No such election"; die(); }

// Handle updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['class'] === 'election') {
        $election->setName($_POST['name']);
        $entityManager->persist($election);
    }

    if ($_POST['class'] === 'post') {
        if (empty($_POST['id'])) {
            $post = new ElectionPost();
        } else {
            $post = $entityManager->find('ElectionPost', $_POST['id']);
            if ($post === null) { echo "No such post"; die(); }
        }

        $post->setName($_POST['name']);
        $post->setType($_POST['type']);
        $post->setNumber($_POST['number']);
        $post->setElection($election);

        $entityManager->persist($post);
    }

    $entityManager->flush();
    header("HTTP/1.1 303 See Other");
    $link = $_SERVER['REQUEST_URI'];
    header("Location: $link");
    die();
}

echo $twig->render('election.html', [
    'election' => $election,
    'posts' => $election->getPosts(),
]);
