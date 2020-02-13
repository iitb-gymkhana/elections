<?php
require_once "bootstrap.php";
require_once "helpers.php";

// Get voting key
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$votingKey = empty($queries['key']) ? null : strtoupper($queries['key']);

// Actual voting logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = $entityManager->find('Election', $_POST['id']);
    if ($election === null) { echo "No such election"; die(); }

    // Election active
    if (!$election->getActive() || $election->getSuspended()) {
        echo "Election not active"; die;
    }

    $electionId = $election->getId();

    // Get voter object
    $query = $entityManager->createQuery(
        "SELECT u FROM ElectionVoter u WHERE u.rollNo = '$USER_ROLL' AND IDENTITY(u.election) = '$electionId'"
    )->setMaxResults(1);
    $voter = $query->getOneOrNullResult();

    // Check existence
    if ($voter === null) {
        echo "No voter found for roll number"; die;
    }

    // Check if duplicate
    if ($voter->getVoted() === true) {
        echo "Voter already voted"; die;
    }

    // Requires voting key
    if ($voter->getVoterList()->getRequireCode() && $votingKey !== $voter->getCode()) {
        echo "Illegal voting key!"; die;
    }

    // Check IP fence
    if (checkIP($voter) === false) {
        echo "IP address not recognized"; die;
    }

    // Mark voted
    $voter->setVoted(true);
    $entityManager->persist($voter);

    foreach ($election->getPosts() as $post) {
        if (!$post->canVote()) {
            continue;
        }

        foreach ($post->getCandidates() as $candidate) {
            // Create vote object
            $evote = new ElectionVote();
            $evote->setCandidate($candidate);
            $evote->setVoterListName($voter->getVoterList()->getName());

            if ($post->isYNN()) {
                $vote = $_POST['c-' . $candidate->getId()];
                if (!isset($vote) || !in_array($vote, ['yes', 'no', 'neutral'])) {
                    echo "Didn't vote for " . $candidate->getName(); die();
                }

                // Create vote object
                $evote->setVote($vote);
                $entityManager->persist($evote);
            } else {
                $vote = $_POST['p-' . $post->getId()];
                if (!isset($vote)) {
                    echo "Didn't vote for " . $post->getName(); die();
                }

                if ($vote === 'nota' || $vote === 'neutral') {
                    $evote->setVote($vote);
                    $entityManager->persist($evote);
                } else if ($vote === strval($candidate->getId())) {
                    $evote->setVote('yes');
                    $entityManager->persist($evote);
                }
            }
        }
    }

    $entityManager->flush();

    echo $twig->render('vote-message.html', [
        'message' => 'Your vote has been recorded!',
        'redir' => 'safe/vote',
    ]);
    die();
}

// Get all elections for voter
$query = $entityManager->createQuery(
    "SELECT u FROM ElectionVoter u WHERE u.rollNo = '$USER_ROLL' ORDER BY u.id ASC");
$voters = $query->getResult();

// Check which election not voted
$election = null;
$voter = null;
$hasBadIP = false;

foreach ($voters as $v) {
    if (!$v->getVoted() && $v->getElection()->getActive() && !$v->getElection()->getSuspended()) {
        if (checkIP($v) !== false) {
            $voter = $v;
            $election = $v->getElection();
            break;
        } else {
            $hasBadIP = true;
        }
    }
}

// Nothing to vote
if ($election === null) {
    echo $twig->render('vote-message.html', [
        'message' => 'No elections for you to vote for right now!',
        'error' => $hasBadIP ? 'Some active elections require you to vote at polling booths' : '',
        'redir' => $LOGOUT_HOME,
    ]);
    die();
}

// Voting key prompt
if ($voter->getVoterList()->getRequireCode() && $votingKey !== $voter->getCode()) {
    echo $twig->render('vote-key.html', [
        'election' => $election,
        'name' => $_SERVER['OIDC_CLAIM_name'],
    ]);
    die();
}

echo $twig->render('vote.html', ['election' => $election]);
