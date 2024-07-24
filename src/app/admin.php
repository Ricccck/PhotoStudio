<?php
namespace photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use photostudio\lib\PDODatabase;
use Photostudio\lib\Admin;
use Photostudio\lib\Photo;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);
$photo = new Photo($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';


$ctgArr = $photo->getCategoryList();
$photoArr = $admin->getPhotoList($ctg_id);


$context = [];
$context['ctgArr'] = $ctgArr;
$context['photoArr'] = $photoArr;
$template = $twig->load('admin.html.twig');
$template->display($context);