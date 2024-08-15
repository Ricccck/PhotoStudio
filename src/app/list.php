<?php
namespace photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Photo;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$photo = new Photo($db);
$client = new Client($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';
$ctgArr = $photo->getCategoryList();

$dataArr = [];
$dataArr['ctg_id'] = $ctg_id;
foreach ($ctgArr as $ctg) {
  $dataArr['ctg_name'] = $ctg['category_id'] === $ctg_id ? $ctg['category'] : '全て';
}

$userArr = [];
if (isset($_SESSION['client'])) {
  $userArr = $client->getData($_SESSION['client']);
} elseif (isset($_SESSION['$customer'])) {
  $userArr = $user->getData($_SESSION['customer']);
} else {
  $userArr['username'] = 'Guest';
}


$keyword = (isset($_GET['keyword']) === true) ? $_GET['keyword'] : '';
$keyword = mb_convert_kana($keyword, 's', 'UTF-8');
$keyword = trim(preg_replace('/\s+/', ' ', $keyword));
$keywordArr = explode(' ', $keyword);

$photoArr = $photo->getPhotoList($ctg_id);
if ($keyword !== '') {
  $photoArr = $photo->getSearchPhotoList($ctg_id, $keywordArr);
}


$context = [];
$context['ctgArr'] = $ctgArr;
$context['dataArr'] = $dataArr;
$context['photoArr'] = $photoArr;
$context['userArr'] = $userArr;
$template = $twig->load('common/list.html.twig');
$template->display($context);