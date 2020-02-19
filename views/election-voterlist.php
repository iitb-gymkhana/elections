<?php

$HAS_ERRORS = false;

function echoError($voterRoll, $error) {
    echo "<div style='color: red'> <b> $voterRoll - $error </b> </div>";
    flush(); ob_flush();
}

// Create or get
$isNew = empty($_POST['id']) && empty($_POST['eid']);
if ($isNew) {
    $voterList = new ElectionVoterList();
    $voterList->setElection($election);
} else {
    $voterList = $entityManager->find('ElectionVoterList', $_POST['id'] ?? $_POST['eid']);
    if ($voterList === null|| $voterList->getElection()->getId() !== $election->getId()) {
        echo "No such voter list"; die();
    }
}

// Check if deleting
if (!empty($_POST['delete']) && $canEdit) {
    $entityManager->remove($voterList);
} else {
    if ($canEdit || $isNew) {
        $voterList->setName($_POST['name']);
    }

    $voterList->setRequireCode(isset($_POST['require_code']));
    $voterList->setBoothIPs($_POST['booths'] ?? '');
    $entityManager->persist($voterList);
}

if (empty($_POST['id'])) {
    // Flush voterlist
    $entityManager->flush();

    // Get voters
    $voters = preg_split("/\r\n|\n|\r/", $_POST['voters']);

    // Stream content
    ignore_user_abort(true);
    set_time_limit(0);

    // Set initial content
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

    // Start LDAP connection
    if ($LDAP_SERVER !== null) {
        $ds = ldap_connect($LDAP_SERVER) or die("Unable to connect to LDAP server. Please try again later.");
    }

    // Insert in batches
    $batchSize = 20;
    foreach ($voters as $i => $voterRoll) {
        if (empty($voterRoll)) continue;

        // Sanitize
        $voterRoll = strtoupper(preg_replace( '/[^a-zA-Z0-9_.-]/', '', $voterRoll));

        // Might get a CN from LDAP
        $cn = '';

        // Verify from LDAP
        if (isset($LDAP_SERVER)) {
            // Get LDAP object
            $sr = ldap_search($ds, "dc=iitb,dc=ac,dc=in", "(employeeNumber=$voterRoll)", array("cn"));
            $entries = ldap_get_entries($ds, $sr);

            // Check if found in LDAP
            if ($entries['count'] <= 0) {
                echoError($voterRoll, 'NOT_FOUND_LDAP');
                $HAS_ERRORS = true;
                continue;
            }

            // Get CN from LDAP object
            $cn = $entries[0]["cn"][0];

            // Give LDAP some time
            usleep(5 * 1000);
        }

        // Check if existing
        if (in_array($voterRoll, $added)) {
            echoError("$voterRoll ($cn)", 'DUPLICATE');
            $HAS_ERRORS = true;
            continue;
        }

        // Create new
        $voter = new ElectionVoter();
        $voter->setRollNo($voterRoll);
        $voter->setVoted(0);
        $voter->setVoterList($voterList);
        $voter->setElection($election);
        $voter->setCode(generateRandomString(6));
        $voter->setName($cn);
        $entityManager->persist($voter);

        // Add to added
        array_push($added, $voterRoll);

        // Show that we are all okay
        echo "<div> $voterRoll ($cn) - OK </div>";
        flush(); ob_flush();

        // Flush to db
        if (($i % $batchSize) == 0) {
            $entityManager->flush();
            $entityManager->clear('ElectionVoter');
        }
    }

    $entityManager->flush();

    if ($HAS_ERRORS === false) {
        echo '<h3> All voters added successfully! </h3>';
    } else {
        echo '<h3 style="color:red"> There were some errors. Other voters were added. </h3>';
    }

    echo '<h3> Refresh this page to continue! </h3>';

    die();
}
