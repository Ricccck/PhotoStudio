<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\ErrCheck;
use Photostudio\lib\Client;
use Photostudio\lib\Photo;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$errCheck = new ErrCheck();
$client = new Client($db);
$photo = new Photo($db);

$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$ctgArr = $photo->getCategoryList();

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
    $dataArr['image'] = $_FILES['image'];


    $errArr = $errCheck->photoErrCheck($dataArr);
    $err_check = $errCheck->getErrorFlg();

    if ($err_check) {
      $dataArr['price'] = $photo->calcPrice($_FILES['image']);

      $titleArr = $photo->movePhotoFile($_FILES['image']);
      [$dataArr['photo_url'], $dataArr['sample_url']] = $titleArr;
    }


    unset($dataArr['image']);
    $template = ($err_check === true) ? 'client/photo_conf.html.twig' : 'client/post.html.twig';

    break;

  case 'back':
    $dataArr = $_POST;

    unset($dataArr['photo_back']);

    foreach ($dataArr as $key => $value) {
      $errArr[$key] = '';
    }

    $template = 'post.html.twig';

    break;

  case 'complete':
    unset($_POST['complete']);

    $dataArr = $_POST;

    $userArr = $client->getData($_SESSION['client']);
    $dataArr['client_id'] = $userArr['client_id'];


    $ctgArr = $photo->getCategoryList();
    foreach ($ctgArr as $arr) {
      if ($dataArr['category'] === $arr['category']) {
        $dataArr['category'] = $arr['category_id'];
      }
    }

    $priceArr = $photo->getPriceList();
    foreach ($priceArr as $arr) {
      if ($dataArr['price'] == $arr['price']) {
        $dataArr['price'] = $arr['price_id'];
      }
    }
    $dataArr['price'] = isset($_POST['is_free']) ? 1 : $dataArr['price'];


    $res = $photo->insPhotoData($dataArr);


    if ($res === true) {
      header('Location: ' . Bootstrap::APP_URL . 'complete.php');
      exit();
    } else {
      $template = 'post.html.twig';

      foreach ($dataArr as $key => $value) {
        $errArr[$key] = '';
      }
    }

    break;
}

$context['dataArr'] = $dataArr;
$context['ctgArr'] = $ctgArr;
$context['errArr'] = $errArr;
$template = $twig->load($template);
$template->display($context);