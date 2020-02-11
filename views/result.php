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
    // Detailed result
    $detail = array();
    $structure = array();

    // Create detail structure
    foreach ($post->getCandidates() as $candidate) {
        $structure[$candidate->getName()] = array(
            'yes' => 0, 'no' => 0, 'nota' => 0, 'neutral' => 0,
        );
    }

    foreach ($post->getCandidates() as $candidate) {
        $nqb = $qb->setParameter('candidate', strval($candidate->getId()));

        $iterableResult = $nqb->getQuery()->iterate();
        foreach ($iterableResult as $row) {
            $vote = $row[0];

            // Get voter list for detailed result
            if (($vList = $vote->getVoterListName()) && $USER_SUPERADMIN) {
                if (!array_key_exists($vList, $detail)) {
                    $detail[$vList] = $structure;
                }

                // Add vote
                if (array_key_exists($vote->getVote(), $detail[$vList][$candidate->getName()])) {
                    $detail[$vList][$candidate->getName()][$vote->getVote()]++;
                }
            }

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

    if ($USER_SUPERADMIN) {
        ksort($detail, SORT_NATURAL);
        $post->resultDetail = $detail;
    }

    $count = $post->getCandidates()->count();
    $post->resultNOTA /= $count;
    $post->resultNeutral /= $count;
}

echo $twig->render('result.html', ['election' => $election ]);
