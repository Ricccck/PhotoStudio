<?php
namespace Photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck();
$client = new Client($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$err_msg = '';


$userArr = [];
if (isset($_SESSION['client'])) {
  $userArr = $client->getData($_SESSION['client']);
} else {
  $userArr['username'] = 'Guest';
}

$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';

if (isset($_GET['action']) && $photo_id !== '') {
  if ($_GET['action'] === 'show') {
    $client->switchPhotoDisplay($photo_id);
  } else if($_GET['action'] === 'delete') {
    $client->actualDeletePhoto($photo_id);
  }
}

$photoArr = $client->getPostPhotoList($_SESSION['client']);


$context = [];
$context['userArr'] = $userArr;
$context['photoArr'] = $photoArr;
$template = $twig->load('client/post_list.html.twig');
$template->display($context);