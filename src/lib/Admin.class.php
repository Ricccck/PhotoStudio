<?php
namespace Photostudio\lib;

class Admin
{
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getPhotoList($ctg_id)
  {
    $table = ' upload_photos up ';
    $col = ' photo_id, photo_url, sample_url ';
    $where = ($ctg_id !== '') ? ' category = ? AND delete_flg = ? AND is_examined = ? ' : ' delete_flg = ? AND is_examined = ? ';

    $arrVal = ($ctg_id !== '') ? [$ctg_id, 0, 0] : [0, 0];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }


  public function getPhotoDetailData($photo_id)
  {
    $table = ' upload_photos up JOIN clients c ON up.client_id = c.client_id JOIN price p ON up.price = p.price_id JOIN category ca ON up.category = ca.category_id ';
    $col = ' photo_id, photo_title, c.client_name, ca.category, tags, sample_url, p.price, upload_date ';
    $where = ($photo_id !== '') ? ' photo_id = ? ' : '';

    $arrVal = ($photo_id !== '') ? [$photo_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    $res[0]['tags'] = json_decode($res[0]['tags']);

    return ($res !== false && count($res) !== 0) ? $res[0] : false;
  }

  public function examinePhoto($photoId)
  {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' upload_photos ';
      $insData = ['is_examined' => 1];
      $where = ' photo_id = ? ';
      $arrWhereVal = [$photoId];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }
}