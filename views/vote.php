<?php
require_once "bootstrap.php";

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$votingKey = empty($queries['key']) ? null : strtoupper($queries['key']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = $entityManager->find('Election', $_POST['id']);
    if ($election === null) { echo "No such election"; die(); }

    // Election active
    if ($election->getActive() !== true) {
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
    if ($election->getRequireCode() && $votingKey !== $voter->getCode()) {
        echo "Illegal voting key!"; die;
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

                if ($vote === 'nota') {
                    $evote->setVote('nota');
                    $entityManager->persist($evote);
                } else if ($vote === strval($candidate->getId())) {
                    $evote->setVote('yes');
                    $entityManager->persist($evote);
                }
            }
        }
    }

    $entityManager->flush();

    header("HTTP/1.1 303 See Other");
    if (!isset($link)) $link = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: $link");
    die();
}

// Get all elections for voter
$query = $entityManager->createQuery(
    "SELECT u FROM ElectionVoter u WHERE u.rollNo = '$USER_ROLL' ORDER BY u.id ASC");
$voters = $query->getResult();

// Check which election not voted
$election = null;
$voter = null;
foreach ($voters as $v) {
    if (!$v->getVoted() && $v->getElection()->getActive() === true) {
        $voter = $v;
        $election = $v->getElection();
    }
}

// Nothing to vote
if ($election === null) {
    header("HTTP/1.1 302");
    header("Location: $BASE_URL");
    die();
}

// Voting key prompt
if ($election->getRequireCode() && $votingKey !== $voter->getCode()) {
    echo $twig->render('vote-key.html', [
        'election' => $election,
        'name' => $_SERVER['OIDC_CLAIM_name'],
    ]);
    die();
}

echo $twig->render('vote.html', ['election' => $election]);
