<?php
require_once "bootstrap.php";

$voterList = $entityManager->find('ElectionVoterList', $vlId);
if ($voterList === null) { echo "No such voter list"; die(); }

$query = $entityManager->createQuery("SELECT u FROM ElectionVoter u WHERE IDENTITY(u.voterList) = '$vlId'");

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $voterList->getName() .'.csv";');
$f = fopen('php://output', 'w');
fputcsv($f, array('Roll Number', 'Code'), ',');

foreach ($query->getResult() as $voter) {
    fputcsv($f, array($voter->getRollNo(), $voter->getCode()), ',');
}
