<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Admin;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';

if (isset($_GET['action']) && $photo_id !== '') {
  if ($_GET['action'] === 'examine') {
    $admin->examinePhoto($photo_id);
  } else if($_GET['action'] === 'delete') {
    $client->actualDeletePhoto($photo_id);
  }
}

$photoArr = $admin->getPhotoList();

$context = [];
$context['photoArr'] = $photoArr;
$template = $twig->load('admin/check.html.twig');
$template->display($context);