<?php
namespace Photostudio\lib;

class Customer
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function regist($dataArr)
  {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' customers ';
      $insData = [
        'username' => $dataArr['username'],
        'last_name' => $dataArr['last_name'],
        'first_name' => $dataArr['first_name'],
        'last_name_kana' => $dataArr['last_name_kana'],
        'first_name_kana' => $dataArr['first_name_kana'],
        'email' => $dataArr['email'],
        'phone_number' => $dataArr['tel1'] . $dataArr['tel2'] . $dataArr['tel3'],
        'sex' => $dataArr['sex'],
        'zip' => $dataArr['zip'],
        'pref' => $dataArr['pref'],
        'city' => $dataArr['city'],
        'town' => $dataArr['town']
      ];

      $this->db->insert($table, $insData);

      $customerId = $this->db->getLastId();


      $this->db->insert('customer_pass', [
        'customer_id' => $customerId,
        'password_hash' => password_hash($dataArr['password'], \PASSWORD_BCRYPT)
      ]);


      $sessionId = $this->generateUniqueSessionId();

      $this->db->insert('sessions', [
        'session_id' => $sessionId,
        'user_id' => $customerId,
        'user_type' => 'customer',
        'is_active' => 1
      ]);


      $this->db->dbh->commit();

      $_SESSION['customer'] = $sessionId;

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function getData($sessionId)
  {
    $table = ' sessions s JOIN customers c ON s.user_id = c.customer_id';
    $column = ' customer_id, username, first_name, last_name, first_name_kana, last_name_kana, email, phone_number, sex, zip, pref, city, town, regist_at ';
    $where = ' session_id = ? AND is_active = ? AND user_type = ? ';
    $arrVal = [$sessionId, 1, 'customer'];

    $res = $this->db->select($table, $column, $where, $arrVal);

    return $res[0];
  }

  public function login($user, $pass)
  {
    $table = ' customer_pass cp JOIN customers c ON cp.customer_id = c.customer_id';
    $column = ' password_hash, c.customer_id ';
    $where = ' username = ? OR email = ? ';
    $arrVal = [$user, $user];

    $res = $this->db->select($table, $column, $where, $arrVal);
    [$hashArr] = $res !== false ? $res : ['password_hash' => ''];

    if (password_verify($pass, $hashArr['password_hash'])) {
      $sessionId = $this->generateUniqueSessionId();

      $this->db->insert('sessions', [
        'session_id' => $sessionId,
        'user_id' => $hashArr['customer_id'],
        'user_type' => 'customer'
      ]);

      $_SESSION['customer'] = $sessionId;

      return true;
    } else {
      return false;
    }
  }

  public function logout($sessionId)
  {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' sessions ';
      $insData = ['is_active' => 0];
      $where = ' session_id = ? ';
      $arrWhereVal = [$sessionId];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      session_destroy();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  private function generateUniqueSessionId()
  {
    do {
      $sessionId = bin2hex(random_bytes(16));

      $table = 'sessions';
      $where = ' session_id = ? ';
      $insData = [$sessionId];

      $count = $this->db->count($table, $where, $insData);

    } while ($count > 0);

    return $sessionId;
  }
}