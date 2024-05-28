<?php
namespace Src;

require_once __DIR__ . '/Bootstrap.class.php';
require_once __DIR__ . '/lib/PDODatabase.class.php';
require_once __DIR__ . '/lib/member/initMaster.class.php';

use Src\Bootstrap;
use Src\lib\PDODatabase;
use Src\lib\member\initMaster;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$dataArr = [
  'family_name' => '',
  'first_name' => '',
  'post_code' => '',
  'address' => '',
  'email' => '',
  'phone_number' => ''
];

$errArr = [];
foreach ($dataArr as $key => $value) {
  $errArr[$key] = '';
}

list($yearArr, $monthArr, $dayArr) = initMaster::getDate();

$context = [];

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
// $context['genderArr'] = $genderArr;

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);