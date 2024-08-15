<?php
namespace Photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;
use Photostudio\lib\Common;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck($db);
$client = new Client($db);
$common = new Common();


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


if (isset($_POST['confirm']) === true) {
  $mode = 'confirm';
}

if (isset($_POST['back']) === true) {
  $mode = 'back';
}

if (isset($_POST['complete']) === true) {
  $mode = 'complete';
}


switch ($mode) {
  case 'confirm':
    unset($_POST['confirm']);

    $dataArr = $_POST;

    $errArr = $errCheck->customerErrCheck($dataArr);
    $err_check = $errCheck->getErrorFlg();
    $template = ($err_check === true) ? 'client/confirm.html.twig' : 'client/regist.html.twig';

    break;

  case 'back':
    $dataArr = $_POST;

    unset($dataArr['back']);

    foreach ($dataArr as $key => $value) {
      $errArr[$key] = '';
    }

    $template = 'client/regist.html.twig';

    break;

  case 'complete':
    $dataArr = $_POST;

    unset($dataArr['complete']);

    $res = $client->regist($dataArr);

    if ($res === true) {
      header('Location: ' . Bootstrap::APP_URL . 'complete.php');
      exit();
    } else {
      $template = 'client/confirm.html.twig';

      foreach ($dataArr as $key => $value) {
        $errArr[$key] = '';
      }
    }

    break;
}

$sexArr = $common->getSex();


$context = [];

$context['user'] = 'client';
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['sexArr'] = $sexArr;
$template = $twig->load($template);
$template->display($context);