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

$photoArr = $photo->getPhotoDetailData($photo_id);

$photoArr['upload_date'] = date('Y年m月d日', strtotime($photoArr['upload_at']));


$userArr = [];
$is_customer = false;
if (isset($_SESSION['client'])) {
  $userArr = $client->getData($_SESSION['client']);
} elseif (isset($_SESSION['customer'])) {
  $userArr = $customer->getData($_SESSION['customer']);
  $is_customer = true;
} else {
  $userArr['username'] = 'Guest';
}



$context = [];
$context['photoArr'] = $photoArr;
$context['isCustomer'] = $is_customer;
$context['userArr'] = $userArr;
$template = $twig->load('common/detail.html.twig');
$template->display($context);