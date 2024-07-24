<?php
namespace Photostudio\lib;

class ErrCheck
{
  private $dataArr = [];
  private $errArr = [];
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function customerErrCheck($dataArr)
  {
    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->userNameCheck();
    $this->isExistedUsername();
    $this->lastNameCheck();
    $this->firstNameCheck();
    $this->lastNameKanaCheck();
    $this->firstNameKanaCheck();
    $this->emailCheck();
    $this->isExistedCusEmail();
    $this->telCheck();
    $this->sexCheck();
    $this->zipCheck();
    $this->addCheck();
    $this->passCheck();
    $this->passConfCheck();

    return $this->errArr;
  }

  public function clientErrCheck($dataArr)
  {
    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->userNameCheck();
    $this->isExistedUsername();
    $this->lastNameCheck();
    $this->firstNameCheck();
    $this->lastNameKanaCheck();
    $this->firstNameKanaCheck();
    $this->emailCheck();
    $this->isExistedCliEmail();
    $this->telCheck();
    $this->sexCheck();
    $this->zipCheck();
    $this->addCheck();
    $this->passCheck();
    $this->passConfCheck();

    return $this->errArr;
  }

  public function photoErrCheck($dataArr)
  {
    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->photoCheck();
    $this->titleCheck();
    $this->ctgCheck();
    $this->tagCheck();

    return $this->errArr;
  }

  private function createErrorMessage()
  {
    foreach ($this->dataArr as $key => $value) {
      $this->errArr[$key] = '';
    }
  }

  private function usernameCheck()
  {
      $pattern = '/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{6,20}$/';
      if ($this->dataArr['username'] === '') {
          $this->errArr['username'] = 'ユーザーネームを入力してください。';
      } else if (!preg_match($pattern, $this->dataArr['username'])) {
          $this->errArr['username'] = '6~20文字の半角英数字（大文字小文字を含む）を入力してください。';
      }
  }  

  private function isExistedUsername()
  {
    $count = $this->db->count('customers', 'username = ?', [$this->dataArr['username']]);
    $count += $this->db->count('clients', 'username = ?', [$this->dataArr['username']]);

    if($count > 0){
      $this->errArr['username'] = '既に使用されているユーザーネームです。';
    }
  }  

  private function lastNameCheck()
  {
    if ($this->dataArr['last_name'] === '') {
      $this->errArr['last_name'] = '氏を入力してください。';
    }
  }

  private function firstNameCheck()
  {
    if ($this->dataArr['first_name'] === '') {
      $this->errArr['first_name'] = '名を入力してください。';
    }
  }

  private function lastNameKanaCheck()
  {
    if ($this->dataArr['last_name_kana'] === '' || !preg_match('/^[ぁ-ん]+?/', $this->dataArr['last_name_kana'])) {
      $this->errArr['last_name_kana'] = '氏をひらがなで入力してください。';
    }
  }

  private function firstNameKanaCheck()
  {
    if ($this->dataArr['first_name_kana'] === '' || !preg_match('/^[ぁ-ん]+?/', $this->dataArr['first_name_kana'])) {
      $this->errArr['first_name_kana'] = '名をひらがなで入力してください。';
    }
  }

  private function emailCheck()
  {
    $pattern = '/^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/';
    if ($this->dataArr['email'] === ''){
      $this->errArr['email'] = 'メールアドレスを入力してください。';
    } else if(!preg_match($pattern, $this->dataArr['email'])) {
      $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください。';
    }
  }

  private function isExistedCusEmail()
  {
    $count = $this->db->count('customers', 'email = ?', [$this->dataArr['email']]);

    if($count > 0){
      $this->errArr['email'] = '既に使用されているメールアドレスです。';
    }
  }

  private function isExistedCliEmail()
  {
    $count = $this->db->count('clients', 'email = ?', [$this->dataArr['email']]);

    if($count > 0){
      $this->errArr['email'] = '既に使用されているメールアドレスです。';
    }
  }

  private function telCheck()
  {
    if (
      !preg_match('/^\d{1,6}$/', $this->dataArr['tel1']) ||
      !preg_match('/^\d{1,6}$/', $this->dataArr['tel2']) ||
      !preg_match('/^\d{1,6}$/', $this->dataArr['tel3']) ||
      strlen($this->dataArr['tel1'] . $this->dataArr['tel2'] . $this->dataArr['tel3']) >= 12
    ) {
      $this->errArr['tel1'] = '半角数字で11桁以内で入力してください。';
    }
  }

  private function sexCheck()
  {
    if ($this->dataArr['sex'] === '0') {
      $this->errArr['sex'] = '性別を選択してください。';
    }
  }
  private function zipCheck()
  {
    if ($this->dataArr['zip'] === '') {
      $this->errArr['zip'] = '郵便番号を入力してください。';
    } else if (!preg_match('/^[0-9\-]{7,8}$/', $this->dataArr['zip'])) {
      $this->errArr['zip'] = '半角数字7桁かハイフン(-)付き8桁で入力してください。';
    }
  }

  private function addCheck()
  {
    if ($this->dataArr['pref'] === '') {
      $this->errArr['pref'] = '都道府県を入力してください。';
    }
    if ($this->dataArr['city'] === '') {
      $this->errArr['city'] = '市区町村を入力してください。';
    }
    if ($this->dataArr['town'] === '') {
      $this->errArr['town'] = '番地以降を入力してください。';
    }
  }

  private function passCheck()
  {
      $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[!-~]{8,20}$/';
  
      if ($this->dataArr['password'] === '') {
          $this->errArr['password'] = 'パスワードを入力してください。';
      } else if (!preg_match($pattern, $this->dataArr['password'])) {
          $this->errArr['password'] = '大文字小文字数字を含む8~20桁の半角英数字で入力してください。';
      }
  }  

  private function passConfCheck()
  {
    if ($this->dataArr['pass_conf'] !== $this->dataArr['password']) {
      $this->errArr['pass_conf'] = 'パスワードと同一のパスワードを入力してください。';
    }
  }

  private function whichCheck()
  {
    if (isset($_POST['customer']) !== true || isset($_POST['client']) !== true) {
      $this->errArr['check'] = 'どちらかを選択してください';
    }
  }

  private function titleCheck()
  {
    if ($this->dataArr['photo_title'] === '') {
      $this->errArr['photo_title'] = '画像タイトルを入力してください。';
    }
  }

  private function ctgCheck()
  {
    if ($this->dataArr['category'] === '') {
      $this->errArr['category'] = 'カテゴリーを選択してください。';
    }
  }

  private function tagCheck()
  {
    $count = 1;
    foreach ($this->dataArr['tags'] as $value) {
      if ($value === '') {
        $count++;
      }
    }

    if ($count > 3) {
      $this->errArr['tags'] = 'この画像に3つ以上のタグを設定してください。';
    }
  }

  private function photoCheck()
  {
    if ($this->dataArr['image']['tmp_name'] === '') {
      $this->errArr['image'] = '画像を選択してください。';
    } else {
      $tmp_image = $this->dataArr['image'];
      if ($tmp_image['error'] === 0 && $tmp_image['size'] !== 0) {
        if (is_uploaded_file($tmp_image['tmp_name']) === true) {
          [$width, $height, $type] = getimagesize($tmp_image['tmp_name']);

          $this->errArr['image_min_size'] = (($width + $height) < 1300) ? '1300px以上の画像をアップロードしてください。' : '';
          $this->errArr['image_max_size'] = (($width + $height) > 35000) ? '35000px以下の画像をアップロードしてください。' : '';

          $this->errArr['image_size'] = ($tmp_image['size'] > 52428800) ? 'アップロードできる画像サイズは、50MBまでです。' : '';

          $this->errArr['image_mime'] = ($type !== 2) ? 'アップロードできる画像形式は、JPEG方式だけです。' : '';
        }
      }
    }


  }

  public function getErrorFlg()
  {
    $err_check = true;
    foreach ($this->errArr as $key => $value) {
      if ($value !== '') {
        $err_check = false;
      }

    }
    return $err_check;
  }
}