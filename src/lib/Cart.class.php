<?php
namespace Photostudio\lib;

class Cart
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function getCartList($customerId)
  {
    $table = ' cart c JOIN upload_photos up ON c.photo_id = up.photo_id LEFT JOIN price p ON up.price = p.price_id ';
    $col = ' crt_id, photo_title, sample_url, p.price ';
    $where = ($customerId !== '') ? ' c.customer_id = ? AND is_purchased = ? AND c.is_deleted = ? ' : '';

    $arrVal = ($customerId !== '') ? [$customerId, 0, 0] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function getPurchasedPhotoList($customerId)
  {
    $table = ' cart c JOIN upload_photos up ON c.photo_id = up.photo_id JOIN price p ON up.price = p.price_id ';
    $col = ' c.photo_id, photo_title, photo_url ';
    $where = ($customerId !== '') ? ' customer_id = ? AND is_purchased = ? AND c.is_deleted = ? ' : '';

    $arrVal = ($customerId !== '') ? [$customerId, 1, 0] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function culcTotalPrice($dataArr)
  {
    $total = 0;
    foreach ($dataArr as $arr) {
      $total += intval($arr['price']);
    }

    return $total;
  }

  public function insCartData($customerId, $photoId)
  {
    $table = ' cart ';
    $insData = [
      'customer_id' => $customerId,
      'photo_id' => $photoId,
    ];

    $res = $this->db->insert($table, $insData);

    return $res;
  }

  public function deletePhoto($crtId)
  {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' cart ';
      $insData = ['is_deleted' => 1];
      $where = ' crt_id = ? ';
      $arrWhereVal = [$crtId];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function purchasePhotos($crtIdArr)
  {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' cart ';
      $insData = ['is_purchased' => 1];
      $where = ' crt_id = ? ';

      foreach ($crtIdArr as $whereVal) {
        $this->db->update($table, $where, $insData, [$whereVal]);
      }

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }
}