<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

use ReflectionClass;
use SetBased\Abc\Form\Cleaner\AmbiguityCleaner;

//----------------------------------------------------------------------------------------------------------------------
class AmbiguityCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  protected $myEmptyValues = ['', false, null];

  protected $myZeroValues = ['0'];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function makeCleaner()
  {
    return new AmbiguityCleaner();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testEmptyStringClean1()
  {
    $raw   = "  Hello\x00\x0d\x07World!  ";
    $clean = '  HelloWorld!  ';

    $this->baseTest($raw, $clean);

    return $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testEmptyStringClean2()
  {
    $raw   = "\x00\x0d\x08";
    $clean = null;

    $this->baseTest($raw, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testLineFeedClean()
  {
    $raw   = "  Hello\x0b\xe2\x80\xa8\xe2\x80\xa9World!  ";
    $clean = "  Hello\n\n\nWorld!  ";

    $null = $this->baseTest($raw, $clean);
    $this->assertNull($null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSpacesClean()
  {
    $raw   = "  Hello\xe1\x9a\x80 \xe2\x80\x8aWorld!  ";
    $clean = '  Hello   World!  ';

    $this->baseTest($raw, $clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test umlauts are preserved. For example: Ö is "\xc3\x96" and "\x96" is a control character that will removed.
   */
  public function testUmlautClean()
  {
    $raw = "ä ö ü ß Ä Ö Ü";
    $this->baseTest($raw, $raw);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Length of ambiguous characters must be 1 character.
   */
  public function testValidUtf8Ambiguities()
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
        $this->assertEquals(1, mb_strlen($ambiguity), sprintf("Length of '%s' is not 1", bin2hex($ambiguity)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function baseTest($raw, $clean)
  {
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->checkEncoding($raw);
    $this->checkEncoding($value);
    $this->checkEncoding($clean);

    $this->assertEquals($clean, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function checkEncoding($var)
  {
    $this->assertTrue(mb_check_encoding($var), sprintf("%s is not valid UTF-8", bin2hex($var)));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

