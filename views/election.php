<?php
require_once "bootstrap.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) { echo "No such election"; die(); }

// Handle updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['class'] === 'election') {
        $election->setName($_POST['name']);

        // Start election
        if (!empty($_POST['start']) && $election->getActive() === false && $election->getEnded() === false) {
            $election->setActive(true);
        }
        // End election
        if (!empty($_POST['end']) && $election->getActive() === true && $election->getEnded() === false) {
            $election->setActive(false);
            $election->setEnded(true);
        }

        $entityManager->persist($election);
    }

    if ($_POST['class'] === 'post') {
        if (empty($_POST['id'])) {
            $post = new ElectionPost();
        } else {
            $post = $entityManager->find('ElectionPost', $_POST['id']);
            if ($post === null) { echo "No such post"; die(); }
        }

        // Check if deleting
        if (!empty($_POST['delete'])) {
            $entityManager->remove($post);
        } else {
            $post->setName($_POST['name']);
            $post->setType($_POST['type']);
            $post->setNumber($_POST['number']);
            $post->setElection($election);

            $entityManager->persist($post);
        }
    }

    if ($_POST['class'] === 'candidate') {
        if (empty($_POST['id'])) {
            $candidate = new ElectionCandidate();
        } else {
            $candidate = $entityManager->find('ElectionCandidate', $_POST['id']);
            if ($candidate === null) { echo "No such candidate"; die(); }
        }

        // Get the linked post
        $post = $entityManager->find('ElectionPost', $_POST['postid']);
        if ($post === null) { echo "No such post"; die(); }

        // Check if deleting
        if (!empty($_POST['delete'])) {
            $entityManager->remove($candidate);
        } else {
            $candidate->setName($_POST['name']);
            $candidate->setPhoto($_POST['photo']);
            $candidate->setManifesto($_POST['manifesto']);
            $candidate->setPost($post);

            $entityManager->persist($candidate);
        }
    }

    if ($_POST['class'] === 'voterlist') {
        if (empty($_POST['id'])) {
            $voterList = new ElectionVoterList();

            $voters = preg_split("/\r\n|\n|\r/", $_POST['voters']);
            foreach ($voters as $voterRoll) {
                if (empty($voterRoll)) continue;

                $voter = new ElectionVoter();
                $voter->setRollNo($voterRoll);
                $voter->setVoted(false);
                $voter->setVoterList($voterList);
                $voter->setElection($election);
                $voter->setCode("FFFFFF");
                $entityManager->persist($voter);
            }

        } else {
            $voterList = $entityManager->find('ElectionVoterList', $_POST['id']);
            if ($voterList === null) { echo "No such voter list"; die(); }
        }

        // Check if deleting
        if (!empty($_POST['delete'])) {
            $entityManager->remove($voterList);
        } else {
            $voterList->setName($_POST['name']);
            $voterList->setElection($election);
            $entityManager->persist($voterList);
        }
    }

    $entityManager->flush();
    header("HTTP/1.1 303 See Other");
    if (!isset($link)) $link = $_SERVER['REQUEST_URI'];
    header("Location: $link");
    die();
}

echo $twig->render('election.html', [
    'election' => $election,
]);
