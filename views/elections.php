<?php
require_once "bootstrap.php";
include "check-admin.php";

// Handle creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = new Election();
    $election->setName($_POST['name']);
    $election->setActive(false);
    $election->setEnded(false);
    $election->setSuspended(false);
    $election->setCreator($USER_ROLL);

    $entityManager->persist($election);
    $entityManager->flush();

    header("HTTP/1.1 303 See Other");
    header("Location: " . $election->getId());
    die();
}

// All elections for super admin, own for others
if ($USER_SUPERADMIN) {
    $elections = $entityManager->getRepository('Election')->findAll();
} else {
    $elections = $entityManager->createQueryBuilder()
        ->select('e')
        ->from('Election', 'e')
        ->where("e.creator = $USER_ROLL")
        ->getQuery()->getResult();
}

echo $twig->render('elections.html', ['elections' => $elections]);
