<?php
namespace Photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Admin;
use Photostudio\lib\Photo;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$photo = new Photo($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';
$ctgArr = $photo->getCategoryList();

$dataArr['ctg_name'] = '全て';
foreach ($ctgArr as $ctg) {
  if($ctg['category_id'] == $ctg_id){
    $dataArr['ctg_name'] = $ctg['category'];
  }
}


$photo_id = (isset($_GET['photo_id']) === true && preg_match('/^[0-9]+$/', $_GET['photo_id']) === 1) ? $_GET['photo_id'] : '';

if (isset($_GET['action']) && $photo_id !== '') {
  if ($_GET['action'] === 'delete') {
    $client->actualDeletePhoto($photo_id);
  }
}


$keyword = (isset($_GET['keyword']) === true) ? $_GET['keyword'] : '';
$keyword = mb_convert_kana($keyword, 's', 'UTF-8');
$keyword = trim(preg_replace('/\s+/', ' ', $keyword));
$keywordArr = explode(' ', $keyword);


$photoArr = $photo->getPhotoList();
if ($keyword !== '') {
  $photoArr = $photo->getSearchPhotoList($ctg_id, $keywordArr);
}

$context = [];
$context['ctgArr'] = $ctgArr;
$context['dataArr'] = $dataArr;
$context['photoArr'] = $photoArr;
$template = $twig->load('admin/list.html.twig');
$template->display($context);