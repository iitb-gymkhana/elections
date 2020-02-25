<?php
require_once "bootstrap.php";
include "check-admin.php";

$voterList = $entityManager->find('ElectionVoterList', $vlId);
if ($voterList === null) {
    http_response_code(404);
    echo "No such voter list"; die();
}
if (!$USER_SUPERADMIN && $voterList->getElection()->getCreator() !== $USER_ROLL) dieNoElection();

$query = $entityManager->createQuery("SELECT u FROM ElectionVoter u WHERE IDENTITY(u.voterList) = '$vlId'");

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $voterList->getName() .'.csv";');
$f = fopen('php://output', 'w');
fputcsv($f, array('Roll Number', 'Name', 'Code'), ',');

$iterableResult = $query->iterate();
foreach ($iterableResult as $row) {
    $voter = $row[0];
    fputcsv($f, array($voter->getRollNo(), $voter->getName(), $voter->getCode()), ',');
    $entityManager->detach($voter);
}
