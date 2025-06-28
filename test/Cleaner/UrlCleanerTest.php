<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\UrlCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Test cases for class UrlCleaner.
 */
class UrlCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of UrlCleaner.
   *
   * @return UrlCleaner
   */
  public function createCleaner(): UrlCleaner
  {
    return UrlCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testFtp(): void
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'ftp://ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('ftp://ftp.setbased.nl/', $value);

    $raw   = 'ftp://user:password@ftp.setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('ftp://user:password@ftp.setbased.nl/', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testHttp(): void
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'www.setbased.nl';
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
  /**
   * Test with invalid URL that can not be parsed.
   */
  public function testInvalid(): void
  {
    $cleaner = UrlCleaner::get();

    $value = 'http://:80';
    $clean = $cleaner->clean($value);
    self::assertSame($value, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testMailTo(): void
  {
    $cleaner = UrlCleaner::get();

    $raw   = 'mailto:info@setbased.nl';
    $value = $cleaner->clean($raw);
    self::assertEquals('mailto:info@setbased.nl', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

