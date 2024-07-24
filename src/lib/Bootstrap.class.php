<?php
namespace Photostudio\lib;

date_default_timezone_set('Asia/Tokyo');
require_once __DIR__ . './../vendor/autoload.php';


class Bootstrap
{
  const DB_HOST = 'mysql';
  const DB_NAME = 'photostudio_db';
  const DB_USER = 'photostudio_user';
  const DB_PASS = 'photostudio_pass';
  const DB_TYPE = 'mysql';
  const APP_DIR = '/var/www/html/';
  const PUBLIC_DIR = self::APP_DIR . 'public/';
  const TEMPLATE_DIR = self::APP_DIR . 'templates/';
  const CACHE_DIR = false;
  const APP_URL = 'http://localhost:8000/';
  const ENTRY_URL = self::APP_URL . 'public/';
}

spl_autoload_register(function ($class) {
  $class = str_replace('Photostudio\\lib\\', '', $class);
  
  $classPath = str_replace('\\', '/', $class);
  
  $filePath = __DIR__ . '/' . $classPath . '.class.php';

  if (file_exists($filePath)) {
      require_once $filePath;
  } else {
      throw new \Exception("Class file not found: $filePath");
  }
});