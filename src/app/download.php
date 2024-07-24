<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Customer;
use Photostudio\lib\Photo;
use Photostudio\lib\Cart;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck();
$customer = new Customer($db);
$photo = new Photo($db);
$cart = new Cart($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$err_msg = '';


$userArr = [];
if (isset($_SESSION['customer'])) {
  $userArr = $customer->getData($_SESSION['customer']);
  $userArr['user_name'] = $userArr['family_name'] . ' ' . $userArr['first_name'];
} else {
  $userArr['user_name'] = 'Guest';
}


$crtArr = $cart->getPurchasedPhotoList($userArr['customer_id']);

$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';


if ($photo_id !== '') {
  $file = __DIR__ . '/upload/' . $photo->getPhotoURL($photo_id);


  if (file_exists($file) && is_readable($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    ob_clean();
    flush();

    readfile($file);
    exit;
  } else {
    $err_msg =  '画像のダウンロードに失敗しました。';
  }
}


$context['userArr'] = $userArr;
$context['crtArr'] = $crtArr;
$context['err_msg'] = $err_msg;
$template = $twig->load('download.html.twig');
$template->display($context);