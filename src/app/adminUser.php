<?php
namespace Photostudio;

require_once  __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\PDODatabase;
use Photostudio\lib\Admin;
use Photostudio\lib\Common;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$admin = new Admin($db);
$common = new Common($db);


$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


$client_id = (isset($_GET['client_id']) === true && preg_match('/^[0-9]+$/', $_GET['client_id']) === 1) ? $_GET['client_id'] : '';
$customer_id = (isset($_GET['customer_id']) === true && preg_match('/^[0-9]+$/', $_GET['customer_id']) === 1) ? $_GET['customer_id'] : '';

if (isset($_GET['action']) && $client_id !== '') {
  if ($_GET['action'] === 'offline') {
    $res = $admin->offlineClient($client_id);
    var_dump($res);
  } else if($_GET['action'] === 'delete') {
    $admin->actualDeleteClient($client_id);
  }
}
if (isset($_GET['action']) && $customer_id !== '') {
  if ($_GET['action'] === 'offline') {
    $admin->offlineCustomer($customer_id);
  } else if($_GET['action'] === 'delete') {
    $admin->actualDeleteCustomer($customer_id);
  }
}


$clientArr = $admin->getClients();
$customerArr = $admin->getCustomers();

$sexArr = $common->getSex();

$context = [];
$context['clientArr'] = $clientArr;
$context['customerArr'] = $customerArr;
$context['sexArr'] = $sexArr;
$template = $twig->load('admin/user.html.twig');
$template->display($context);