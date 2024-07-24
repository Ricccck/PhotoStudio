<?php
namespace Photostudio\lib;

class Common
{
    private $db = null;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function getSex()
    {
        $sexArr = ['0' => '', '1' => '男性', '2' => '女性', '9' => 'その他'];
        return $sexArr;
    }

    public function formatPhoneNumber($phoneNumber)
    {
        $formattedNumber = '';

        if (preg_match('/^0[789]0\d{8}$/', $phoneNumber)) {
            $formattedNumber = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '$1-$2-$3', $phoneNumber);
        } elseif (preg_match('/^0[1-9]{1}[0-9]{1}[0-9]{8}$/', $phoneNumber)) {
            $formattedNumber = preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '$1-$2-$3', $phoneNumber);
        } elseif (preg_match('/^0[1-9]{1}[0-9]{2}[0-9]{7}$/', $phoneNumber)) {
            $formattedNumber = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3', $phoneNumber);
        } else {
            $formattedNumber = $phoneNumber;
        }

        return $formattedNumber;
    }

    public function formatZip($zip)
    {
        if (preg_match('/^\d{7}$/', $zip)) {
            return preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', $zip);
        } else {
            return $zip;
        }
    }
}