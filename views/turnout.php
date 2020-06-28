<?php
require_once "bootstrap.php";
include "check-admin.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) dieNoElection();
if (!$USER_SUPERADMIN &&
        ($election->getCreator() !== $USER_ROLL)
    ) dieNoElection();

// Compute turnout
$voterLists = $election->getVoterLists();
foreach ($voterLists as $vl) {
    $vl->getTurnoutCount($entityManager);
    $vl->getRegisteredCount($entityManager);
}

$dummy = new Election();
$dummy->setName($election->getName());
echo $twig->render('result.html', [
    'election' => $dummy,
    'voterLists' => $voterLists,
]);
