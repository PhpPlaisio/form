<?php

namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Test\AbcTestCase;

/**
 * Abstract parent test cases for cleaners.
 */
abstract class CleanerTest extends AbcTestCase
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

      self::assertNull($cleaned, sprintf("Cleaning '%s' must return null.", addslashes($value)));
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

      self::assertEquals('0', $cleaned, sprintf("Cleaning '%s' must return '0'.", addslashes($value)));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
