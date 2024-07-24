<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

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
$photoArr = $photo->getPhotoList($ctg_id);


$dataArr = [];
if($photoArr !== false){
$randomPhoto = array_rand($photoArr);
$dataArr['random_id'] = $photoArr[$randomPhoto]['photo_id'];
$dataArr['random_url'] = $photoArr[$randomPhoto]['photo_url'];
}

$userArr = [];
if (isset($_SESSION['client'])) {
  $userArr = $client->getData($_SESSION['client']);
} elseif (isset($_SESSION['customer'])) {
  $userArr = $customer->getData($_SESSION['customer']);
} else {
  $userArr['username'] = 'Guest';
}

// var_dump(password_hash('test', \PASSWORD_BCRYPT));

$context = [];
$context['ctgArr'] = $ctgArr;
$context['photoArr'] = $photoArr;
$context['dataArr'] = $dataArr;
$context['userArr'] = $userArr;
$template = $twig->load('home.html.twig');
$template->display($context);