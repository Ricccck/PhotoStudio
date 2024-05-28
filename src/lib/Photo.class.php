<?php
namespace Src\Lib;

class Photo
{
  public $cateArr = [];
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getPhotoList($photo_id)
  {
    $table = ' upload_photos ';
    $col = ' photo_url ';
    $where = ($photo_id !== '') ? ' id = ? ' : '';

    $arrVal = ($photo_id !== '') ? [$photo_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function getPhotoDetailData($photo_id){
    $table = ' upload_photos ';
    $col = ' id, client_id, photo_url, sample_url, price, update_date ';
    $where = ($photo_id !== '') ? ' id = ? ' : '';

    $arrVal = ($photo_id !== '') ? [$photo_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }
}