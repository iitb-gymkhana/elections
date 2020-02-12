<?php

if (empty($_POST['id'])) {
    $post = new ElectionPost();
    $post->setElection($election);
} else {
    $post = $entityManager->find('ElectionPost', $_POST['id']);
    if ($post === null || $post->getElection()->getId() !== $election->getId()) {
        echo "No such post"; die();
    }
}

// Check if deleting
if (!empty($_POST['delete'])) {
    $entityManager->remove($post);
} else {
    $post->setName($_POST['name']);
    $post->setType($_POST['type']);
    $post->setNumber($_POST['number']);
    $post->setMOrder($_POST['order']);

    $entityManager->persist($post);
}
