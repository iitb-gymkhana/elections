<?php
require_once "bootstrap.php";
include "check-admin.php";

use Doctrine\ORM\Tools\Pagination\Paginator;

// Handle creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election = new Election();
    $election->setName($_POST['name']);
    $election->setActive(false);
    $election->setEnded(false);
    $election->setSuspended(false);
    $election->setCreator($USER_ROLL);
    $election->setTime($_POST['time']);

    $entityManager->persist($election);
    $entityManager->flush();

    http_response_code(303);
    header("Location: $BASE_URL" . 'safe/admin/' . $election->getId());
    die();
}

// Get page number
$queries = array(); parse_str($_SERVER['QUERY_STRING'], $queries);
$page = intval($queries['p'] ?? 0);

// All elections for super admin, own for others
$pageSize = 100;
$qb = $entityManager->createQueryBuilder()
        ->select('e')
        ->orderBy('e.id', 'DESC')
        ->from('Election', 'e')
        ->setFirstResult($page * $pageSize)
        ->setMaxResults($pageSize);

if (!$USER_SUPERADMIN) {
    $qb = $qb->where("e.creator = '$USER_ROLL'");
}

$query = $qb->getQuery();
$elections = new Paginator($query);
$pageCount = intval(ceil(count($elections) / $pageSize));

echo $twig->render('elections.html', [
    'elections' => $elections,
    'next' => ($page >= $pageCount - 1) ? null : $page + 1,
    'prev' => ($page <= 0) ? null : $page - 1,
    'superadmin' => $USER_SUPERADMIN,
]);
