<?php
require_once "bootstrap.php";
include "check-admin.php";
include "helpers.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) dieNoElection();
if (!$USER_SUPERADMIN && $election->getCreator() !== $USER_ROLL) dieNoElection();

// Handle updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if modification allowed
    $canEdit = !$election->getActive() && !$election->getEnded();

    if ($_POST['class'] === 'election') {
        include 'election-election.php';
    }

    else if ($_POST['class'] === 'post' && $canEdit) {
        include 'election-post.php';
    }

    else if ($_POST['class'] === 'candidate' && $canEdit) {
        include 'election-candidate.php';
    }

    else if ($_POST['class'] === 'voterlist') {
        include 'election-voterlist.php';
    }

    $entityManager->flush();
    header("HTTP/1.1 303 See Other");
    if (!isset($link)) $link = $_SERVER['REQUEST_URI'];
    header("Location: $link");
    die();
}

// Get JSON of voterlists
$q = $entityManager->createQueryBuilder()
        ->select('evl.name, evl.id')
        ->from('ElectionVoterList', 'evl')
        ->where('IDENTITY(evl.election) = ' . $election->getId())
        ->getQuery();
$vlJson = json_encode($q->getResult());

// Get voterlist counts
foreach ($election->getVoterLists() as $vl) {
    $vl->getRegisteredCount($entityManager);
}

echo $twig->render('election.html', [
    'election' => $election,
    'allVoterListsJson' => $vlJson,
]);
