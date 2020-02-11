<?php
require_once "bootstrap.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) { echo "No such election"; die(); }

echo $twig->render('vote.html', ['elections' => [ $election ] ]);
