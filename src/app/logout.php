<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Client;
use Photostudio\lib\Customer;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$client = new Client($db);
$customer = new Customer($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


if (isset($_SESSION['client'])) {
  $client->logout($_SESSION['client']);
} elseif (isset($_SESSION['customer'])) {
  $customer->logout($_SESSION['customer']);
}

$context = [];
$template = $twig->load('authentication/logout.html.twig');
$template->display($context);