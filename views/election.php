<?php
require_once "bootstrap.php";
include "check-admin.php";
include "helpers.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) { echo "No such election"; die(); }
if (!$USER_SUPERADMIN && $election->getCreator() !== $USER_ROLL) dieNoElection();

// Handle updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if modification allowed
    $canEdit = !$election->getActive() && !$election->getEnded();

    if ($_POST['class'] === 'election') {
        if (!$election->getEnded()) {
            // Start election
            if (!empty($_POST['start'])) {
                $election->setActive(true);
            }

            // End election
            if ($election->getActive()) {
                if (!empty($_POST['end'])) {
                    $election->setSuspended(false);
                    $election->setActive(false);
                    $election->setEnded(true);
                }

                // Suspend/resume election
                if (!empty($_POST['suspend'])) {
                    $election->setSuspended(!$election->getSuspended());
                }
            }
        }

        // Update
        if (isset($_POST['name'])) {
            $election->setName($_POST['name']);
            $election->setRequireCode(isset($_POST['require_code']));
        }

        $entityManager->persist($election);
    }

    else if ($_POST['class'] === 'post' && $canEdit) {
        if (empty($_POST['id'])) {
            $post = new ElectionPost();
            $post->setElection($election);
        } else {
            $post = $entityManager->find('ElectionPost', $_POST['id']);
            if ($post === null || $post->getElection()->getId() !== $election->getId()) {
                echo "No such post"; die();
            }
        }

        // Check if deleting
        if (!empty($_POST['delete'])) {
            $entityManager->remove($post);
        } else {
            $post->setName($_POST['name']);
            $post->setType($_POST['type']);
            $post->setNumber($_POST['number']);
            $post->setMOrder($_POST['order']);

            $entityManager->persist($post);
        }
    }

    else if ($_POST['class'] === 'candidate' && $canEdit) {
        if (empty($_POST['id'])) {
            $candidate = new ElectionCandidate();
            $candidate->setPost($post);
        } else {
            $candidate = $entityManager->find('ElectionCandidate', $_POST['id']);
            if ($candidate === null || $candidate->getPost()->getElection()->getId() !== $election->getId()) {
                echo "No such candidate"; die();
            }
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
            $candidate->setMOrder($_POST['order']);

            $entityManager->persist($candidate);
        }
    }

    else if ($_POST['class'] === 'voterlist') {
        if (empty($_POST['id']) && $canEdit) {
            $voterList = new ElectionVoterList();
            $voterList->setElection($election);

            $voters = preg_split("/\r\n|\n|\r/", $_POST['voters']);
            foreach ($voters as $voterRoll) {
                if (empty($voterRoll)) continue;

                $voter = new ElectionVoter();
                $voter->setRollNo($voterRoll);
                $voter->setVoted(false);
                $voter->setVoterList($voterList);
                $voter->setElection($election);
                $voter->setCode(generateRandomString(6));
                $entityManager->persist($voter);
            }

        } else {
            $voterList = $entityManager->find('ElectionVoterList', $_POST['id']);
            if ($voterList === null|| $voterList->getElection()->getId() !== $election->getId()) {
                echo "No such voter list"; die();
            }
        }

        // Check if deleting
        if (!empty($_POST['delete']) && $canEdit) {
            $entityManager->remove($voterList);
        } else {
            if ($canEdit) {
                $voterList->setName($_POST['name']);
            }

            $voterList->setBoothIPs($_POST['booths']);
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
    'superAdmin' => $USER_SUPERADMIN,
]);
