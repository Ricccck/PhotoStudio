<?php
namespace Src\lib\member;

class ErrCheck
{
  private $dataArr = [];
  private $errArr = [];

  public function __construct()
  {
  }

  public function errClientCheck($dataArr)
  {
    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->nameCheck();
    $this->postCheck();
    $this->addCheck();
    $this->emailCheck();
    $this->telCheck();

    return $this->errArr;
  }

  public function errCustomerCheck($dataArr){
    $this->dataArr = $dataArr;
    $this->createErrorMessage();
    $this->firstNameCheck();
    $this->familyNameCheck();
    $this->emailCheck();
  }

  private function createErrorMessage()
  {
    foreach ($this->dataArr as $key => $value) {
      $this->errArr[$key] = '';
    }
  }

  private function nameCheck()
  {
    if ($this->dataArr['name'] === '') {
      $this->errArr['name'] = 'お名前を入力してください。';
    }
  }

  private function familyNameCheck()
  {
    if ($this->dataArr['family_name'] === '') {
      $this->errArr['family_name'] = 'お名前（氏）を入力してください。';
    }
  }

  private function firstNameCheck()
  {
    if ($this->dataArr['first_name'] === '') {
      $this->errArr['first_name'] = 'お名前（名）を入力してください。';
    }
  }

  private function postCheck()
  {
    if (preg_match('/^[0-9]{7}$/', $this->dataArr['post_code']) === 0) {
      $this->errArr['post_code'] = '郵便番号は半角数字7桁で入力してください。';
    }

  }

  private function addCheck()
  {
    if ($this->dataArr['address'] === '') {
      $this->errArr['address'] = '住所を入力してください。';
    }
  }

  private function emailCheck()
  {
    if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->dataArr['email']) === 0) {
      $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください。';
    }
  }

  private function telCheck()
  {
    if (
      preg_match('/^\d{1,11}$/', $this->dataArr['phone_number']) === 0 ||
      strlen($this->dataArr['phone_number']) >= 12
    ) {
      $this->errArr['phone_number'] = '電話番号は、半角数字で11桁以内で入力してください';
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