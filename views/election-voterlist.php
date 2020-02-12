<?php

if (empty($_POST['id'])) {
    $voterList = new ElectionVoterList();
    $voterList->setElection($election);

    $voters = preg_split("/\r\n|\n|\r/", $_POST['voters']);
    foreach ($voters as $voterRoll) {
        if (empty($voterRoll)) continue;

        $voter = new ElectionVoter();
        $voter->setRollNo(strtoupper($voterRoll));
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
    if ($canEdit || empty($_POST['id'])) {
        $voterList->setName($_POST['name']);
    }

    $voterList->setRequireCode(isset($_POST['require_code']));
    $voterList->setBoothIPs($_POST['booths']);
    $entityManager->persist($voterList);
}
