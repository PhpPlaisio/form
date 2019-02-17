<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Cleaner;

use ReflectionClass;
use SetBased\Abc\Form\Cleaner\AmbiguityCleaner;
use SetBased\Abc\Form\Cleaner\Cleaner;

/**
 * Test cases for class AmbiguityCleaner.
 */
class AmbiguityCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  protected $emptyValues = ['', false, null];

  protected $zeroValues = ['0'];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner(): Cleaner
  {
    return new AmbiguityCleaner();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testEmptyStringClean1(): string
  {
    $raw   = "  Hello\x00\x0d\x07World!  ";
    $clean = '  HelloWorld!  ';

    $this->baseTest($raw, $clean);

    return $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testEmptyStringClean2(): void
  {
    $raw   = "\x00\x0d\x08";
    $clean = null;

    $this->baseTest($raw, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testLineFeedClean(): void
  {
    $raw   = "  Hello\x0b\xe2\x80\xa8\xe2\x80\xa9World!  ";
    $clean = "  Hello\n\n\nWorld!  ";

    $this->baseTest($raw, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSpacesClean(): void
  {
    $raw   = "  Hello\xe1\x9a\x80 \xe2\x80\x8aWorld!  ";
    $clean = '  Hello   World!  ';

    $this->baseTest($raw, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test umlauts are preserved. For example: Ö is "\xc3\x96" and "\x96" is a control character that will removed.
   */
  public function testUmlautClean(): void
  {
    $raw = "ä ö ü ß Ä Ö Ü";
    $this->baseTest($raw, $raw);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Length of ambiguous characters must be 1 character.
   */
  public function testValidUtf8Ambiguities(): void
  {
    $cleaner = new AmbiguityCleaner();

    $reflection = new ReflectionClass($cleaner);
    $property   = $reflection->getProperty('ambiguities');
    $property->setAccessible(true);
    $unambiguities = $property->getValue($cleaner);

    foreach ($unambiguities as $unambiguity => $ambiguities)
    {
      foreach ($ambiguities as $ambiguity)
      {
        $this->checkEncoding($ambiguity);
        self::assertEquals(1, mb_strlen($ambiguity), sprintf("Length of '%s' is not 1", $ambiguity));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function baseTest(string $raw, ?string $clean): void
  {
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->checkEncoding($raw);
    $this->checkEncoding($value);
    $this->checkEncoding($clean);

    self::assertEquals($clean, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function checkEncoding($var): void
  {
    if ($var!==null)
    {
      self::assertTrue(mb_check_encoding($var), sprintf("%s is not valid UTF-8", $var));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

