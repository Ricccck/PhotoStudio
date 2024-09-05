<?php
namespace Photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;
use Photostudio\lib\Common;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck($db);
$client = new Client($db);
$customer = new Customer($db);
$common = new Common();


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$errArr = [];
$dataArr = [];
if (isset($_POST['send'])) {
  unset($_POST['send']);
  foreach ($_POST as $key => $value) {
    if ($_POST[$key] !== '') {
      $dataArr[$key] = $value;
    }
  }

  if (isset($_SESSION['client'])) {
    $client_id = $_POST['client_id'];
    unset($dataArr['client_id']);

    $res = $client->edit($dataArr, $client_id);
  } elseif (isset($_SESSION['customer'])) {
    $customer_id = $_POST['customer_id'];
    unset($dataArr['customer_id']);

    var_dump($dataArr);

    $res = $customer->edit($dataArr, $customer_id);
    var_dump($res);
  }
}


$userArr = [];
if (isset($_SESSION['client'])) {
  $userArr = $client->getData($_SESSION['client']);

  $userArr['zip'] = $common->formatZip($userArr['zip']);
  $userArr['phone_number'] = $common->formatPhoneNumber($userArr['phone_number']);
} elseif (isset($_SESSION['customer'])) {
  $userArr = $customer->getData($_SESSION['customer']);

  $userArr['zip'] = $common->formatZip($userArr['zip']);
  $userArr['phone_number'] = $common->formatPhoneNumber($userArr['phone_number']);
} else {
  header('Location: ' . Bootstrap::APP_URL . 'home.php');
}

$sexArr = $common->getSex();

$context = [];
$context['sexArr'] = $sexArr;
$context['userArr'] = $userArr;
$context['errArr'] = $errArr;
$context['dataArr'] = $dataArr;
$template = $twig->load('common/mypage.html.twig');
$template->display($context);