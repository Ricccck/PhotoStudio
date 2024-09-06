<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Photo;
use Photostudio\lib\Client;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$photo = new Photo($db);
$client = new Client($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


if($_SESSION['client'] === null) {
  header("Location: " . Bootstrap::ENTRY_URL . "home.php");
}


$ctgArr = $photo->getCategoryList();

$userArr = $client->getData($_SESSION['client']);

$context = [];
$context['ctgArr'] = $ctgArr;
$context['userArr'] = $userArr;
$template = $twig->load('client/post.html.twig');
$template->display($context);