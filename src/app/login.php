<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck();
$client = new Client($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';

$isCustomer = (isset($_POST['customer']) && $_POST['customer'] === 'on') ? true : false;
$isClient = (isset($_POST['client']) && $_POST['client'] === 'on') ? true : false;

$dataArr = [
  'email' => '',
  'password' => ''
];

$errArr = [];
foreach ($dataArr as $key => $value) {
  $errArr[$key] = '';
}

if ($isCustomer) {
  $isLoggedIn = $customer->login($_POST['email'], $_POST['password']);
  if ($isLoggedIn) {
    header("Location: " . Bootstrap::APP_URL . "home.php");
  } else {
    $errArr['login'] = 'ログインに失敗しました';
  }
} elseif ($isClient) {
  $isLoggedIn = $client->login($_POST['email'], $_POST['password']);
  if ($isLoggedIn) {
    header("Location: " . Bootstrap::APP_URL . "home.php");
  } else {
    $errArr['login'] = 'ログインに失敗しました';
  }
}



$context['errArr'] = $errArr;
$context['user'] = 'guest';
$template = $twig->load('authentication/login.html.twig');
$template->display($context);