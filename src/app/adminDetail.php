<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Admin;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';
$is_examined = (isset($_POST['examine']) && $_POST['examine'] !== '') ? true : false;


$photoArr = $admin->getPhotoDetailData($photo_id);

$photoArr['upload_date'] = date('Y年m月d日', strtotime($photoArr['upload_date']));


if ($is_examined) {
  $photo_id = (isset($_POST['photo_id']) === true && preg_match('/^[0-9]+$/', $_POST['photo_id']) === 1) ? $_POST['photo_id'] : '';
  $res = $admin->examinePhoto($photo_id);

  if ($res === false) {
    echo "商品承認に失敗しました。";
  } else {
  header('Location: ' . Bootstrap::ENTRY_URL . 'admin.php');
  exit();
  }
}

$context = [];
$context['photoArr'] = $photoArr;
$template = $twig->load('admin_detail.html.twig');
$template->display($context);