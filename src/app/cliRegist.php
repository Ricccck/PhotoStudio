<?php
namespace Photostudio;

require_once __DIR__ . '/../lib/Bootstrap.class.php';

use Photostudio\lib\Bootstrap;
use Photostudio\lib\Common;

$common = new Common();

$loader = new \Twig\Loader\FilesystemLoader(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$dataArr = [
  'username' => '',
  'first_name' => '',
  'last_name' => '',
  'first_name_kana' => '',
  'last_name_kana' => '',
  'company_name' => '',
  'email' => '',
  'tel1' => '',
  'tel2' => '',
  'tel3' => '',
  'sex' => '',
  'zip' => '',
  'pref' => '',
  'city' => '',
  'town' => '',
  'website' => '',
  'password' => '',
  'pass_conf' => ''
];

$sexArr = $common->getSex();

$errArr = [];
foreach ($dataArr as $key => $value) {
  $errArr[$key] = '';
}

$context = [];

$context['user'] = 'client';
$context['sexArr'] = $sexArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->load('client/regist.html.twig');
$template->display($context);