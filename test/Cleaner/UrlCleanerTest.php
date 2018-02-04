<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\UrlCleaner;

/**
 * Test cases for class UrlCleaner.
 */
class UrlCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner()
  {
    return UrlCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testFtp()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'ftp://ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('ftp://ftp.setbased.nl/', $value);

    $raw   = '  ftp://user:password@ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('ftp://user:password@ftp.setbased.nl/', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testHttp()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'www.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/', $value);

    $raw   = '  www.setbased.nl  ';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/', $value);

    $raw   = 'www.setbased.nl/';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/', $value);

    $raw   = 'www.setbased.nl/index.php';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/index.php', $value);

    $raw   = 'www.setbased.nl#here';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/#here', $value);

    $raw   = 'www.setbased.nl?query';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/?query', $value);

    $raw   = 'http://www.setbased.nl?query';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/?query', $value);

    $raw   = 'www.setbased.nl?a=1;b=2';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/?a=1;b=2', $value);

    $raw   = 'www.setbased.nl/test/index.php?a=1;b=2';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/test/index.php?a=1;b=2', $value);

    $raw   = 'www.setbased.nl/test/test';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/test/test', $value);

    $raw   = 'www.setbased.nl/test/test/index.php?a=1;b=2#here';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/test/test/index.php?a=1;b=2#here', $value);

    $raw   = 'http://www.setbased.nl/test/test/index.php?a=1;b=2#here';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/test/test/index.php?a=1;b=2#here', $value);

    $raw   = 'http://www.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('http://www.setbased.nl/', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testMailTo()
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'mailto:info@setbased.nl ';
    $value = $cleaner->clean($raw);
    self::assertEquals('mailto:info@setbased.nl', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * UrlCleaner must return 'http://0/' (instead of '0').
   */
  public function testZeroValues()
  {
    $cleaner = $this->makeCleaner();

    foreach ($this->zeroValues as $value)
    {
      $cleaned = $cleaner->clean($value);

      self::assertEquals('http://0/', $cleaned, sprintf("Cleaning '%s' must return 'http://0'.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

