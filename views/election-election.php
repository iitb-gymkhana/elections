<?php

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
}

$entityManager->persist($election);
