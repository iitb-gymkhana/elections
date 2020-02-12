<?php

// Get the linked post
$post = $entityManager->find('ElectionPost', $_POST['postid']);
if ($post === null) { echo "No such post"; die(); }

// Get or create candidate
if (empty($_POST['id'])) {
    $candidate = new ElectionCandidate();
    $candidate->setPost($post);
} else {
    $candidate = $entityManager->find('ElectionCandidate', $_POST['id']);
    if ($candidate === null || $candidate->getPost()->getElection()->getId() !== $election->getId()) {
        echo "No such candidate"; die();
    }
}

// Check if deleting
if (!empty($_POST['delete'])) {
    $entityManager->remove($candidate);
} else {
    $candidate->setName($_POST['name']);
    $candidate->setPhoto($_POST['photo']);
    $candidate->setManifesto($_POST['manifesto']);
    $candidate->setMOrder($_POST['order']);

    $entityManager->persist($candidate);
}
