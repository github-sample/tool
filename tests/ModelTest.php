<?php
class ModelTest extends PHPUnit_Framework_TestCase
{
  /**
   * @test
   */
  public function getDisplaySize_元の大きさが180、分割数が3、1枚目の場合、大きさが90() 
  {
    $expected = 90;
    $this->assertEquals($expected, Model::getDisplaySize(180, 3, 0));
  }                       

  /**
   * @test
   */
  public function getDisplaySize_元の大きさが180、分割数が3、2枚目の場合、大きさが135() 
  {
    $expected = 135;
    $this->assertEquals($expected, Model::getDisplaySize(180, 3, 1));
  }                       

  /**
   * @test
   */
  public function getDisplaySize_元の大きさが180、分割数が3、3枚目の場合、大きさが180() 
  {
    $expected = 180;
    $this->assertEquals($expected, Model::getDisplaySize(180, 3, 2));
  }                       

  /**
   * @test
   */
  public function getDisplayPosition_元の大きさが180、生成する画像の大きさが90の場合、開始位置が45() 
  {
    $expected = 45;
    $this->assertEquals($expected, Model::getDisplayPosition(180, 90));
  }                       
  
  /**
   * @test
   */
  public function getDisplayPosition_元の大きさが180、生成する画像の大きさが135の場合、開始位置が22．5() 
  {
    $expected = 22.5;
    $this->assertEquals($expected, Model::getDisplayPosition(180, 135));
  }                       

  /**
   * @test
   */
  public function getDisplayPosition_元の大きさが180、生成する画像の大きさが180の場合、開始位置が0() 
  {
    $expected = 0;
    $this->assertEquals($expected, Model::getDisplayPosition(180, 180));
  }                       

  /**
   * @test
   */
  public function calcImage_元の画像の幅が250、元の画像の高さが150、分割数が3、1枚目の場合のデータ() 
  {
    $expected = array('width' => 250, 
                      'height' => 150, 
                      'dispWidth' => 125.0, 
                      'dispHeight' => 75.0, 
                      'dispX' => 62.5, 
                      'dispY' => 37.5);
    $this->assertEquals($expected, Model::calcImage(250, 150, 3, 0));
  }                       

  /**
   * @test
   */
  public function calcImage_元の画像の幅が250、元の画像の高さが150、分割数が3、2枚目の場合のデータ() 
  {
    $expected = array('width' => 250, 
                      'height' => 150, 
                      'dispWidth' => 187.5, 
                      'dispHeight' => 112.5, 
                      'dispX' => 31.25, 
                      'dispY' => 18.75);
    $this->assertEquals($expected, Model::calcImage(250, 150, 3, 1));
  }                       

  /**
   * @test
   */
  public function calcImage_元の画像の幅が250、元の画像の高さが150、分割数が3、3枚目の場合のデータ() 
  {
    $expected = array('width' => 250, 
                      'height' => 150, 
                      'dispWidth' => 250.0, 
                      'dispHeight' => 150.0, 
                      'dispX' => 0.0, 
                      'dispY' => 0.0);
    $this->assertEquals($expected, Model::calcImage(250, 150, 3, 2));
  }                       
  
  /**
   * @test
   */
  public function addCopyright_コピーライトが正しく追加されているか()
  {
    // 正解データ
    $expectedImage = new Imagick(__DIR__.'/images/withCopyright.jpg');
    $expectedIdent = $expectedImage->identifyImage();
    $expectedImage->clear();

    // addCopyright()を実行してその結果を一時保存
    $image = new Imagick(__DIR__.'/images/test.jpg');
    $imageData = array('width' => 1024, 'height' => 768, 'dispWidth' => 1024, 'dispHeight' => 768, 'dispX' => 0, 'dispY' => 0);
    $image = Model::addCopyright($image, $imageData);
    $image->writeImage(__DIR__.'/images/test_copyright_after.jpg');
    $image->clear();

    // 一時保存された画像データ
    $image = new Imagick(__DIR__.'/images/test_copyright_after.jpg');
    $ident = $image->identifyImage();
    $image->clear();
    unlink(__DIR__.'/images/test_copyright_after.jpg');

    // ファイル名は異なるので意図的に空にする
    $expectedIdent['imageName'] = '';
    $ident['imageName'] = '';

    $this->assertEquals($expectedIdent, $ident);
  }
  
  /**
   * @test
   */
  public function addLinework_集中線が正しく追加されているか()
  {
    // 正解データ
    $expectedImage = new Imagick(__DIR__.'/images/withLinework.jpg');
    $expectedIdent = $expectedImage->identifyImage();
    $expectedImage->clear();

    // addCopyright()を実行してその結果を一時保存
    $image = new Imagick(__DIR__.'/images/test.jpg');
    $imageData = array('width' => 1024, 'height' => 768, 'dispWidth' => 1024, 'dispHeight' => 768, 'dispX' => 0, 'dispY' => 0);
    $image = Model::addLinework($image, $imageData);
    $image->writeImage(__DIR__.'/images/test_linework_after.jpg');
    $image->clear();

    // 一時保存された画像データ
    $image = new Imagick(__DIR__.'/images/test_linework_after.jpg');
    $ident = $image->identifyImage();
    $image->clear();
    unlink(__DIR__.'/images/test_linework_after.jpg');

    // ファイル名は異なるので意図的に空にする
    $expectedIdent['imageName'] = '';
    $ident['imageName'] = '';

    $this->assertEquals($expectedIdent, $ident);
  }




} 

