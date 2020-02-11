<?php
require_once "bootstrap.php";

$elections = $entityManager->getRepository('Election')->findAll();

echo $twig->render('vote.html', ['elections' => $elections]);
