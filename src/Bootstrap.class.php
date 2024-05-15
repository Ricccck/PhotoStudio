<?php
namespace Src;

class Bootstrap
{
  const DB_HOST = 'mysql';
  const DB_NAME = 'photostudio_db';
  const DB_USER = 'photostudio_user';
  const DB_PASS = 'photostudio_pass';
  const DB_TYPE = 'mysql';
  const APP_DIR =  __DIR__;
  const TEMPLATE_DIR = __DIR__ . '/../template/';
  const CACHE_DIR = false;
  const APP_URL = 'http://localhost:8080/';

  // public static function loadClass($class)
  // {

  //   // クラスファイルのパスを生成
  //   $path = __DIR__ . '/' . str_replace('\\', '/', $class) . '.class.php';

  //   // クラスファイルが存在する場合、読み込む
  //   if (file_exists($path)) {
  //     require_once $path;
  //   }
  // }
}

// spl_autoload_register(['Src\Bootstrap', 'loadClass']);
