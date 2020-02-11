<?php
require_once "bootstrap.php";
include "check-admin.php";

// Get election
$election = $entityManager->find('Election', $electionId);
if ($election === null) { echo "No such election"; die(); }
if (!$USER_SUPERADMIN &&
        ($election->getCreator() !== $USER_ROLL ||
         !$election->getEnded())
    ) dieNoElection();

// Compute result
$qb = $entityManager->createQueryBuilder()
    ->select('count(v.id)')
    ->from('ElectionVote', 'v')
    ->where('v.vote = :vote')
    ->andWhere('IDENTITY(v.candidate) = :candidate');

foreach ($election->getPosts() as $post) {
    foreach ($post->getCandidates() as $candidate) {
        $nqb = $qb->setParameter('candidate', strval($candidate->getId()));

        $candidate->resultYes = $nqb->setParameter('vote', 'yes')->getQuery()->getSingleScalarResult();
        $candidate->resultNo = $nqb->setParameter('vote', 'no')->getQuery()->getSingleScalarResult();
        $candidate->resultNeutral = $nqb->setParameter('vote', 'neutral')->getQuery()->getSingleScalarResult();
        $post->resultNOTA += $nqb->setParameter('vote', 'nota')->getQuery()->getSingleScalarResult();
    }

    $post->resultNOTA /= $post->getCandidates()->count();
}

echo $twig->render('result.html', ['election' => $election ]);
