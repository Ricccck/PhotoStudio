<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Photo;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$photo = new Photo($db);
$client = new Client($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';

if (isset($_GET['action']) && $photo_id !== '') {
  if($_GET['action'] === 'delete') {
    $res = $client->actualDeletePhoto($photo_id);

    if($res){
      header("Location: " . Bootstrap::APP_URL . "admin.php");
    }
  }
}


$photo = $photo->getPhotoDetailData($photo_id);

$photo['upload_at'] = date('Y年m月d日', strtotime($photo['upload_at']));


$context = [];
$context['photo'] = $photo;
$template = $twig->load('admin/detail.html.twig');
$template->display($context);