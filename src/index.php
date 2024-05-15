<?php
namespace Src;

require_once __DIR__ . '/Bootstrap.class.php';
require_once __DIR__ . '/lib/PDODatabase.class.php';

use Src\Bootstrap;
use Src\lib\PDODatabase;


$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

