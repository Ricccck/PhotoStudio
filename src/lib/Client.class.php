<?php
namespace Photostudio\lib;

class Client
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
      $table = ' clients ';
      $insData = [
        'username' => $dataArr['username'],
        'last_name' => $dataArr['last_name'],
        'first_name' => $dataArr['first_name'],
        'last_name_kana' => $dataArr['last_name_kana'],
        'first_name_kana' => $dataArr['first_name_kana'],
        'company_name' => $dataArr['company_name'],
        'email' => $dataArr['email'],
        'phone_number' => $dataArr['tel1'] . $dataArr['tel2'] . $dataArr['tel3'],
        'sex' => $dataArr['sex'],
        'zip' => $dataArr['zip'],
        'pref' => $dataArr['pref'],
        'city' => $dataArr['city'],
        'town' => $dataArr['town'],
        'website' => $dataArr['website']
      ];

      $this->db->insert($table, $insData);

      $clientId = $this->db->getLastId();


      $this->db->insert('client_pass', [
        'client_id' => $clientId,
        'password_hash' => password_hash($dataArr['password'], \PASSWORD_BCRYPT)
      ]);


      $sessionId = $this->generateUniqueSessionId();

      $this->db->insert('sessions', [
        'session_id' => $sessionId,
        'user_id' => $clientId,
        'user_type' => 'client',
        'is_active' => 1
      ]);


      $this->db->dbh->commit();

      $_SESSION['client'] = $sessionId;

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }

  public function getData($sessionId)
  {
    $table = ' sessions s JOIN clients c ON s.user_id = c.client_id';
    $column = ' client_id, username, first_name, last_name, first_name_kana, last_name_kana, email, phone_number, sex, zip, pref, city, town, regist_at ';
    $where = ' session_id = ? AND is_active = ? AND user_type = ? ';
    $arrVal = [$sessionId, 1, 'client'];

    $res = $this->db->select($table, $column, $where, $arrVal);

    return $res[0];
  }

  public function login($user, $pass)
  {
    $table = ' client_pass cp JOIN clients c ON cp.client_id = c.client_id';
    $column = ' password_hash, c.client_id ';
    $where = ' username = ? OR email = ? ';
    $arrVal = [$user, $user];

    $res = $this->db->select($table, $column, $where, $arrVal);

    if ($res !== []) {
      $result = password_verify($pass, $res[0]['password_hash']);
      if($result){
        $sessionId = $this->generateUniqueSessionId();

        $this->db->insert('sessions', [
          'session_id' => $sessionId,
          'user_id' => $res[0]['client_id'],
          'user_type' => 'client'
        ]);
  
        $_SESSION['client'] = $sessionId;
  
        return true;
      } else {
        return '正しいメールアドレスとパスワードを入力してください。';
      }
    } else {
      return '正しいメールアドレスとパスワードを入力してください。';
    }
  }

  public function edit($dataArr, $client_id){
    $this->db->dbh->beginTransaction();

    try{
      $table = ' clients ';
      $insData = $dataArr;
      $where = ' client_id = ? ';
      $arrWhereVal = [$client_id];

      $this->db->update($table, $where, $insData, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e){
      $this->db->dbh->rollBack();

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


  public function getPostPhotoList($sessionId)
  {
    $res = $this->getData($sessionId);
    $clientId = $res['client_id'];

    $table = ' upload_photos up JOIN clients c ON up.client_id = c.client_id JOIN category ca ON up.category = ca.category_id JOIN price p ON up.price = p.price_id ';
    $col = ' photo_id, photo_title, photo_url, c.username, ca.category, tags, sample_url, p.price, is_examined, upload_at, up.is_deleted ';
    $where = ($clientId !== '') ? ' up.client_id = ? ' : '';

    $arrVal = ($clientId !== '') ? [$clientId] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    foreach($res as &$arr){
      $arr['tags'] = json_decode($arr['tags']);
      $arr['upload_at'] = date('Y年m月d日', strtotime($arr['upload_at']));
    }

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function switchPhotoDisplay($photoId){
    $this->db->dbh->beginTransaction();

    try {
      $res = $this->db->select(' upload_photos ', ' is_deleted ', ' photo_id = ?', [$photoId]);
      $flg = ($res[0]['is_deleted'] === 0) ? 1 : 0;

      $table = ' upload_photos ';
      $insData = ['is_deleted' => $flg];
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

  public function actualDeletePhoto($photoId) {
    $this->db->dbh->beginTransaction();

    try {
      $table = ' upload_photos ';
      $where = ' photo_id = ? ';
      $arrWhereVal = [$photoId];

      $this->db->delete($table, $where, $arrWhereVal);

      $this->db->dbh->commit();

      return true;
    } catch (\Exception $e) {
      $this->db->dbh->rollBack();

      return false;
    }
  }
}