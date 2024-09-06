<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Cart;
use Photostudio\lib\Customer;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$cart = new Cart($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$photo_id = (isset($_POST['photo_id']) === true && preg_match('/^[0-9]+$/', $_POST['photo_id']) === 1) ? $_POST['photo_id'] : '';
$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/', $_GET['crt_id']) === 1) ? $_GET['crt_id'] : '';


$userArr = [];
if (isset($_SESSION['customer'])) {
  $userArr = $customer->getData($_SESSION['customer']);
} else {
  $userArr['username'] = 'Guest';
}
$customer_id = $userArr['customer_id'];

$errArr = [];


if ($photo_id !== '') {
  $res = $cart->insCartData($customer_id, $photo_id);

  if ($res === false) {
    $errArr['added'] = '既にカートに追加済み、もしくは購入済みです。(photo_id : ' . $photo_id . ')';
  }
}

if ($crt_id !== '') {
  $res = $cart->deletePhoto($crt_id);

  if ($res === false) {
    $errArr['deleted'] = "商品削除に失敗しました。";
  }
}

if (isset($_POST['purchase'])) {
  $res = $cart->purchasePhotos($_POST['crt_ids']);

  if ($res === false) {
    $errArr['added'] = "商品購入に失敗しました。";
  }
}


$crtArr = $cart->getCartList($customer_id);
$totalPrice = $cart->culcTotalPrice($crtArr);


$context = [];
$context['crtArr'] = $crtArr;
$context['errArr'] = $errArr;
$context['userArr'] = $userArr;
$context['totalPrice'] = $totalPrice;
$template = $twig->load('customer/cart.html.twig');
$template->display($context);