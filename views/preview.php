<?php
require_once "bootstrap.php";
include "check-admin.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) dieNoElection();
if (!$USER_SUPERADMIN && $election->getCreator() !== $USER_ROLL) dieNoElection();

echo $twig->render('vote.html', [
    'election' => $election,
    'preview' => true,
]);
