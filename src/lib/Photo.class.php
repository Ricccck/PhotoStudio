<?php
namespace Photostudio\lib;

class Photo
{
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getCategoryList()
  {
    $table = ' category ';
    $col = ' category_id, category, img_url ';

    $res = $this->db->select($table, $col);

    return $res;
  }

  public function getPriceList()
  {
    $table = ' price ';
    $col = ' price_id, price ';

    $res = $this->db->select($table, $col);

    return $res;
  }

  public function getPhotoList($ctg_id = '')
  {
    $table = ' upload_photos up ';
    $col = ' photo_id, photo_title, photo_url, sample_url';
    $where = ($ctg_id !== '') ? ' category = ? AND is_deleted = ? AND is_examined = ? ' : ' is_deleted = ? AND is_examined = ? ';

    $arrVal = ($ctg_id !== '') ? [$ctg_id, 0, 1] : [0, 1];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function getSearchPhotoList($ctg_id, $keywordArr)
  {
    $table = ' upload_photos ';
    $col = ' photo_id, photo_title, photo_url, sample_url';
    $where = ($ctg_id !== '') ? ' category = ? AND is_deleted = ? AND is_examined = ? ' : ' is_deleted = ? AND is_examined = ? ';

    $titleLike = '';
    for ($i = 0; $i < count($keywordArr); $i++) {
      $str = " photo_title LIKE '%" . $keywordArr[$i] . "%' ";

      $titleLike .= ($i === 0) ? $str : 'OR' . $str;
    }

    $tagsLike = '';
    foreach ($keywordArr as $keyword) {
      $str = " tags LIKE '%" . $keyword . "%' ";
      $tagsLike .= 'OR' . $str;
    }

    $where .= 'AND ( ' . $titleLike . $tagsLike . ' ) ';

    $arrVal = ($ctg_id !== '') ? [$ctg_id, 0, 1] : [0, 1];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function getPhotoDetailData($photo_id)
  {
    $table = ' upload_photos up JOIN clients c ON up.client_id = c.client_id JOIN price p ON up.price = p.price_id JOIN category ca ON up.category = ca.category_id ';
    $col = ' photo_id, photo_title, c.username, ca.category, tags, sample_url, p.price, upload_at ';
    $where = ($photo_id !== '') ? ' photo_id = ? ' : '';

    $arrVal = ($photo_id !== '') ? [$photo_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    $res[0]['tags'] = json_decode($res[0]['tags']);

    return ($res !== false && count($res) !== 0) ? $res[0] : false;
  }

  public function getPhotoURL($photo_id)
  {
    $table = ' upload_photos ';
    $col = ' photo_url ';
    $where = ($photo_id !== '') ? ' photo_id = ? ' : '';

    $arrVal = ($photo_id !== '') ? [$photo_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res[0]['photo_url'] : false;
  }

  public function insPhotoData($dataArr)
  {
    $table = ' upload_photos ';
    $insData = [
      'photo_title' => $dataArr['photo_title'],
      'client_id' => $dataArr['client_id'],
      'photo_url' => $dataArr['photo_url'],
      'sample_url' => $dataArr['sample_url'],
      'category' => $dataArr['category'],
      'price' => $dataArr['price'],
      'tags' => json_encode($dataArr['tags'])
    ];

    return $this->db->insert($table, $insData);
  }

  public function calcPrice($photo)
  {
    [$width, $height] = getimagesize($photo['tmp_name']);
    $size = $width + $height;
    $priceArr = $this->getPriceList();

    if ($size > 8000) {
      return $priceArr[4]['price'];
    } elseif ($size > 4000) {
      return $priceArr[3]['price'];
    } elseif ($size > 1300) {
      return $priceArr[2]['price'];
    }

  }

  public function movePhotoFile($photo)
  {
    $image_title = 'upload_' . time() . '.jpg';
    $sample_title = 'sample_' . time() . '.jpg';

    $img_path = '../app/public/upload/' . $image_title;
    $sample_path = '../app/public/sample/' . $sample_title;

    if (
      move_uploaded_file($photo['tmp_name'], $img_path) &&
      copy($img_path, $sample_path)
    ) {
      if ($this->createSamplePhoto($sample_path)) {

        $titleArr = [
          $image_title,
          $sample_title
        ];

        return $titleArr;
      }

      return false;
    }
  }

  private function createSamplePhoto($path)
  {
    $image = imagecreatefromjpeg($path);

    $text = 'SAMPLE';
    $fontPath = '/fonts/BlackOpsOne-Regular.ttf';

    $textColor = imagecolorallocate($image, 255, 255, 255);

    $angle = 0;

    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    if ($imageHeight <= $imageWidth) {
      $fontSize = $imageHeight * 0.2;
    } else {
      $fontSize = $imageWidth * 0.1;
    }

    $textBox = imagettfbbox($fontSize, $angle, $fontPath, $text);

    $textWidth = $textBox[2] - $textBox[0];
    $textHeight = $textBox[1] - $textBox[7];

    $x = intval($imageWidth / 2 - $textWidth / 2);
    $y = intval($imageHeight / 2 - $textHeight / 2 + $textHeight);

    imagettftext(
      $image,
      $fontSize,
      $angle,
      $x,
      $y,
      $textColor,
      $fontPath,
      $text
    );

    imagejpeg($image, $path);

    imagedestroy($image);

    return true;
  }
}