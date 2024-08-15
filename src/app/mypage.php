<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;
use Photostudio\lib\Common;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$client = new Client($db);
$customer = new Customer($db);
$common = new Common();


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);



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
  header('Location: ' . Bootstrap::ENTRY_URL . 'home.php');
}

$context = [];
$context['userArr'] = $userArr;
$template = $twig->load('common/mypage.html.twig');
$template->display($context);