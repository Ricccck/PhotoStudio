<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck($db);
$client = new Client($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$dataArr = [
  'email' => '',
  'password' => '',
  'user' => ''
];

$errArr = [];
foreach ($dataArr as $key => $value) {
  $errArr[$key] = '';
}

if (isset($_POST['login'])) {
  $dataArr = $_POST;
  $dataArr['user'] = isset($dataArr['user']) ? $dataArr['user'] : '';
  $errArr = $errCheck->loginCheck($dataArr);
  $err_check = $errCheck->getErrorFlg();
  $isLoggedIn = false;

  if($err_check && $dataArr['user'] === "customer"){
    $isLoggedIn = $customer->login($dataArr['email'], $dataArr['password']);
  } elseif ($err_check && $dataArr['user'] === "client"){
    $isLoggedIn = $client->login($dataArr['email'], $dataArr['password']);
  }

  if ($isLoggedIn === true) {
    header("Location: " . Bootstrap::APP_URL . "home.php");
  } else {
    $errArr['login'] = $isLoggedIn;
  }
}


$context['errArr'] = $errArr;
$template = $twig->load('authentication/login.html.twig');
$template->display($context);