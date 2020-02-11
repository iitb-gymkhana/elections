<?php
require_once "bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = $entityManager->find('Election', $_POST['id']);
    if ($election === null) { echo "No such election"; die(); }

    foreach ($election->getPosts() as $post) {
        if ($post->isYNN()) {
            foreach ($post->getCandidates() as $candidate) {
                $vote = $_POST['c-' . $candidate->getId()];
                if (!isset($vote)) {
                    echo "Didn't vote for " . $candidate->getName(); die();
                }

                echo "Voted for candidate as #$vote";
            }
        } else {
            $vote = $_POST['p-' . $post->getId()];
            if (!isset($vote)) {
                echo "Didn't vote for " . $post->getName(); die();
            }
            echo "Voted for candidate #$vote";
        }
    }
    die();
}

$election = $entityManager->getRepository('Election')->findAll()[0];

echo $twig->render('vote.html', ['election' => $election]);
