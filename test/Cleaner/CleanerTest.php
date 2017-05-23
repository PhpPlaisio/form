<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Form\Cleaner\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class CleanerTest
 */
abstract class CleanerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  protected $emptyValues = ['', false, null, ' ', '  ', "\n", "\n \n", "\n \t"];

  protected $zeroValues = ['0', ' 0 ', "\t 0 \n"];

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Creates a cleaner.
   *
   * @return Cleaner
   */
  abstract public function makeCleaner();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * All cleaners must return null when cleaning empty (i.e. only whitespace) values.
   */
  public function testEmptyValues()
  {
    $cleaner = $this->makeCleaner();

    foreach ($this->emptyValues as $value)
    {
      $cleaned = $cleaner->clean($value);

      $this->assertNull($cleaned, sprintf("Cleaning '%s' must return null.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Most cleaners must return '0' when cleaning '0' values.
   */
  public function testZeroValues()
  {
    $cleaner = $this->makeCleaner();

    foreach ($this->zeroValues as $value)
    {
      $cleaned = $cleaner->clean($value);

      $this->assertEquals('0', $cleaned, sprintf("Cleaning '%s' must return '0'.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
