<?php
namespace Photostudio\lib;

class Admin
{
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function countPhoto()
  {
    $table = " upload_photos ";
    $where = " is_examined = ? ";
    $arrVal = [0];

    $res = $this->db->count($table, $where, $arrVal);

    return $res;
  }

  public function countActiveUser()
  {
    $table = " sessions ";
    $where = ' is_active = ? ';
    $arrVal = [1];

    $res = $this->db->count($table, $where, $arrVal);

    return $res;
  }

  public function getPhotoList()
  {
    $table = ' upload_photos up JOIN clients c ON up.client_id = c.client_id JOIN category ca ON up.category = ca.category_id JOIN price p ON up.price = p.price_id ';
    $col = ' photo_id, photo_title, photo_url, c.username, ca.category, tags, photo_url, sample_url, p.price, is_examined, upload_at, up.is_deleted ';
    $where = ' up.is_deleted = ? AND is_examined = ? ';
    $arrVal = [0, 0];

    $res = $this->db->select($table, $col, $where, $arrVal);

    foreach ($res as &$arr) {
      $arr['tags'] = json_decode($arr['tags']);
      $arr['upload_at'] = date('Y年m月d日', strtotime($arr['upload_at']));

      $imagePath = __DIR__ . '/../app/public/upload/' . $arr['photo_url'];
      $imageInfo = getimagesize($imagePath);

      $arr['size'] = [
        'width' => number_format($imageInfo[0]),
        'height' => number_format($imageInfo[1]),
        'pixels' => number_format($imageInfo[0] * $imageInfo[1])
      ];
    }

    return ($res !== false && count($res) !== 0) ? $res : false;
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

  public function getClients()
  {
    $table = ' clients ';
    $col = ' client_id, username, first_name, last_name, first_name_kana, last_name_kana, company_name, email, phone_number, sex, zip, pref, city, pref, town, website, regist_at, is_deleted ';

    $clientsRes = $this->db->select($table, $col);

    if ($clientsRes) {
      $table = ' sessions ';
      $col = ' session_id, user_id, created_at, is_active';
      $where = ' user_type = ? ';
      $arrVal = ['client'];

      $sessionRes = $this->db->select($table, $col, $where, $arrVal);

      foreach ($clientsRes as &$client) {
        foreach ($sessionRes as $session)
          if ($client['client_id'] === $session['user_id']) {
            $client['session_id'] = $session['session_id'];
            $client['is_active'] = $session['is_active'];
          }
      }
    }

    return ($clientsRes !== false && count($clientsRes) !== 0) ? $clientsRes : false;
  }

  public function offlineClient($clientId){
    $this->db->dbh->beginTransaction();

    try {
      $table = ' sessions ';
      $insData = ['is_active' => 0];
      $where = ' user_id = ?  AND user_type = ? ';
      $arrWhereVal = [$clientId, 'client'];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function actualDeleteClient($clientId) {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' clients ';
      $where = ' client_id = ? ';
      $arrWhereVal = [$clientId];

      $this->db->delete($table, $where, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function getCustomers()
  {
    $table = ' customers ';
    $col = ' customer_id, username, first_name, last_name, first_name_kana, last_name_kana, email, phone_number, sex, zip, pref, city, pref, town, regist_at, is_deleted ';

    $customerRes = $this->db->select($table, $col);

    if ($customerRes) {
      $table = ' sessions ';
      $col = ' session_id, user_id, created_at, is_active';
      $where = ' user_type = ? ';
      $arrVal = ['customer'];

      $sessionRes = $this->db->select($table, $col, $where, $arrVal);

      foreach ($customerRes as &$customer) {
        foreach ($sessionRes as $session)
          if ($customer['customer_id'] === $session['user_id']) {
            $customer['session_id'] = $session['session_id'];
            $customer['is_active'] = $session['is_active'];
          }
      }
    }

    return ($customerRes !== false && count($customerRes) !== 0) ? $customerRes : false;
  }

  public function offlineCustomer($customerId){
    $this->db->dbh->beginTransaction();

    try {
      $table = ' sessions ';
      $insData = ['is_active' => 0];
      $where = ' user_id = ? AND user_type = ? ';
      $arrWhereVal = [$customerId, 'customer'];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function actualDeleteCustomer($customerId) {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' customers ';
      $where = ' customer_id = ? ';
      $arrWhereVal = [$customerId];

      $this->db->delete($table, $where, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }
}