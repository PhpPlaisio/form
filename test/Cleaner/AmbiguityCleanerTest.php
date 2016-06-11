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
    $raw     = "  Hello\x00\x0d\x07World!  ";
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->assertEquals('  HelloWorld!  ', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testEmptyStringClean2()
  {
    $raw     = "\x00\x0d\x08";
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->assertNull($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Length of ambiguous characters must be 1 character.
   */
  public function testLengthAmbiguities()
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
        $this->assertEquals(1, mb_strlen($ambiguity), sprintf("Length of '%s' is not 1", bin2hex($ambiguity)));
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testLineFeedClean()
  {
    $raw     = "  Hello\x0b\xe2\x80\xa8\xe2\x80\xa9World!  ";
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->assertEquals("  Hello\n\n\nWorld!  ", $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSpacesClean()
  {
    $raw     = "  Hello\xe1\x9a\x80 \xe2\x80\x8aWorld!  ";
    $cleaner = AmbiguityCleaner::get();
    $value   = $cleaner->clean($raw);

    $this->assertEquals('  Hello   World!  ', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

