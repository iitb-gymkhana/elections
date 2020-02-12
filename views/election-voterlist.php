<?php

$HAS_ERRORS = false;

function echoError($voterRoll, $error) {
    echo "<div style='color: red'> <b> $voterRoll - $error </b> </div>";
    flush(); ob_flush();
}

if (empty($_POST['id'])) {
    $voterList = new ElectionVoterList();
    $voterList->setElection($election);

    $voters = preg_split("/\r\n|\n|\r/", $_POST['voters']);

    // Stream content
    ignore_user_abort(true);
    set_time_limit(0);

    header('Content-type: text/html; charset=utf-8');
    echo 'Adding voters list ... <br/>';
    flush(); ob_flush();

    // List of already added voters
    $q = $entityManager->createQueryBuilder()
        ->select('ev.rollNo')
        ->from('ElectionVoter', 'ev')
        ->where('IDENTITY(ev.election) = ' . $election->getId())
        ->getQuery();
    $added = array_column($q->getResult(), 'rollNo');

    foreach ($voters as $voterRoll) {
        if (empty($voterRoll)) continue;

        // Sanitize
        $voterRoll = strtoupper(preg_replace( '/[\W]/', '', $voterRoll));

        // Check if existing
        if (in_array($voterRoll, $added)) {
            echoError($voterRoll, 'Duplicate');
            $HAS_ERRORS = true;
            continue;
        }

        // Create new
        $voter = new ElectionVoter();
        $voter->setRollNo($voterRoll);
        $voter->setVoted(false);
        $voter->setVoterList($voterList);
        $voter->setElection($election);
        $voter->setCode(generateRandomString(6));
        $entityManager->persist($voter);

        // Add to added
        array_push($added, $voterRoll);

        // Show that we are all okay
        echo "<div> $voterRoll - OK </div>";
        flush(); ob_flush();
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

if (empty($_POST['id'])) {
    $entityManager->flush();
    if ($HAS_ERRORS === false) {
        echo '<h3> All voters added successfully! </h3>';
    } else {
        echo '<h3 style="color:red"> There were some errors. Other voters were added. </h3>';
    }

    echo '<h3> Refresh this page to continue! </h3>';

    die();
}
