<?php
class Model {
  public function __construct()
  {
  }
    
  public function dispatch()
  {
    // アップロード先
    $uploadPath = './upload/';

    // ファイル名
    $filename = $_FILES['upload']['name'];

    // tmp名
    $tmpname = $_FILES['upload']['tmp_name'];

    // エラー
    $error = $_FILES['upload']['error'];

    // ファイルサイズ
    $size = $_FILES['upload']['size'];
    
    // フォームで入力された画像の分割数
    $divide = $_POST['divide'];

    // エラーが無く、ファイルサイズが0ではない場合アップロード
    if ($error === 0 && $size > 0) {
      move_uploaded_file($tmpname, $uploadPath.$filename);
    } else {
      return;
    }

    $image = new Imagick($uploadPath.$filename);

    // 元の画像サイズをImagickの関数で取得
    $width = $image->getImageWidth();
    $height = $image->getImageHeight();

    $image->clear();

    for ($i = 0; $i < $divide; $i++) {
      $imageData = $this->calcImage($width, $height, $divide, $i);

      $tmpImage = new Imagick($uploadPath.$filename);
      $tmpImage->cropImage($imageData['dispWidth'], $imageData['dispHeight'], $imageData['dispX'], $imageData['dispY']);
      $tmpImage = $this->addLinework($tmpImage, $imageData);
      $tmpImage = $this->addCopyright($tmpImage, $imageData);
      $tmpImage->writeImage(__DIR__ . '/../output/'.preg_replace('/(.+)(\.[^.]+$)/', '$1', $filename).'_'.$i.'.jpg');
      $tmpImage->clear();
      $data['msg'] = '<img src="./output/'.preg_replace('/(.+)(\.[^.]+$)/', '$1', $filename).'_'.$i.'.jpg" width="300"><br>' . $data['msg'];

    }


    return $data;
  }



  /**
   * 生成する画像の大きさ
   * @param int $size 元の大きさ
   * @param int $divide 分割数
   * @param int $num 分割の何枚目なのかのインデックス番号
   * @return int or double $dispSize 生成する画像の大きさ
   */
  public function getDisplaySize($size, $divide, $num)
  {
    $dispSize = $size * 1 / 2 + $size * 1 / 2 * 1 / ($divide - 1) * $num;
    return $dispSize;
  }

  /**
   * 生成する画像の切り取り開始位置
   * @param int $size 元の大きさ
   * @param int $dispSize 生成する画像の大きさ
   * @return int or double $dispPosition 生成する画像の切り取り開始位置
   */
  public function getDisplayPosition($size, $dispSize)
  {
    $dispPosition = ($size - $dispSize) * 1 / 2;
    return $dispPosition;
  }

  /**
   * サイズ・座標の計算
   * @param int $width 元画像の幅
   * @param int $height 元画像の高さ
   * @param int $divide 分割数
   * @param int $num 分割の何枚目なのかのインデックス番号
   * @return array $imageData
   */
  public function calcImage($width, $height, $divide, $num)
  {
    $imageData = array();
    // 元画像の幅
    $imageData['width'] = $width;
    
    // 元画像の高さ
    $imageData['height'] = $height;
    
    // 表示する画像の幅
    $imageData['dispWidth'] = Model::getDisplaySize($width, $divide, $num);
    
    // 表示する画像の高さ
    $imageData['dispHeight'] = Model::getDisplaySize($height, $divide, $num);
    
    // 表示する画像を切り取るX座標
    $imageData['dispX'] = Model::getDisplayPosition($width, $imageData['dispWidth']);

    // 表示する画像を切り取るY座標
    $imageData['dispY'] = Model::getDisplayPosition($height, $imageData['dispHeight']);

    return $imageData;
  }

  /**
   * コピーライトを追加
   * @param imagick $image
   * @param array $imageData calcImage()で計算した結果
   * @return imagick
   */
  public function addCopyright($image, $imageData)
  {
    $text = '(C)ikenyal';
    $draw = new ImagickDraw();
    $fontSize = (int)(32 * $imageData['dispWidth'] / $imageData['width']);
    $draw->setFontSize($fontSize);
    $draw->setFillColor('#ff0000');
    $metrics = $image->queryFontMetrics($draw, $text);
    $x = $imageData['dispWidth'] - $metrics['textWidth'] + $imageData['dispX'] - $fontSize * 0.5;
    $y = $imageData['dispHeight'] - $metrics['textHeight'] + $imageData['dispY'] + $fontSize * 0.5;
    $draw->annotation($x, $y, $text);
    $image->drawImage($draw);
    return $image;
  }

  /**
   * 集中線を追加
   * @param imagick $image
   * @param array $imageData calcImage()で計算した結果
   * @return imagick
   */
  public function addLinework($image, $imageData)
  {
    $frameImage = new Imagick(__DIR__ . '/../linework.png');
    $frameImage->scaleimage($imageData['dispWidth'], $imageData['dispHeight']);
    $image->compositeImage($frameImage, imagick::COMPOSITE_DEFAULT, 0, 0);
    return $image;
  }


}

