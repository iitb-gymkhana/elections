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
    ->select('v')
    ->from('ElectionVote', 'v')
    ->where('IDENTITY(v.candidate) = :candidate');

foreach ($election->getPosts() as $post) {
    foreach ($post->getCandidates() as $candidate) {
        $nqb = $qb->setParameter('candidate', strval($candidate->getId()));

        $iterableResult = $nqb->getQuery()->iterate();
        foreach ($iterableResult as $row) {
            $vote = $row[0];
            switch ($vote->getVote()) {
                case 'yes':
                    $candidate->resultYes++;
                break;
                case 'no':
                    $candidate->resultNo++;
                break;
                case 'neutral':
                    $candidate->resultNeutral++;
                    $post->resultNeutral++;
                break;
                case 'nota':
                    $post->resultNOTA++;
                break;
            }

            $entityManager->detach($vote);
        }
    }

    $count = $post->getCandidates()->count();
    $post->resultNOTA /= $count;
    $post->resultNeutral /= $count;
}

echo $twig->render('result.html', ['election' => $election ]);
