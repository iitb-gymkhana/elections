<?php
require_once "bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = $entityManager->find('Election', $_POST['id']);
    if ($election === null) { echo "No such election"; die(); }

    foreach ($election->getPosts() as $post) {
        foreach ($post->getCandidates() as $candidate) {
            // Create vote object
            $evote = new ElectionVote();
            $evote->setCandidate($candidate);

            if ($post->isYNN()) {
                $vote = $_POST['c-' . $candidate->getId()];
                if (!isset($vote) || !in_array($vote, ['yes', 'no', 'neutral'])) {
                    echo "Didn't vote for " . $candidate->getName(); die();
                }

                // Create vote object
                $evote->setVote($vote);
                $entityManager->persist($evote);

                echo "Voted for candidate as #$vote";
            } else {
                $vote = $_POST['p-' . $post->getId()];
                if (!isset($vote)) {
                    echo "Didn't vote for " . $post->getName(); die();
                }

                if ($vote === 'nota') {
                    $evote->setVote('nota');
                    $entityManager->persist($evote);
                } else if ($vote === strval($candidate->getId())) {
                    $evote->setVote('yes');
                    $entityManager->persist($evote);
                }

                echo "Voted for candidate #$vote";
            }
        }
    }

    $entityManager->flush();
    die();
}

$election = $entityManager->getRepository('Election')->findAll()[0];

echo $twig->render('vote.html', ['election' => $election]);
