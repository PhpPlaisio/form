<?php

namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\MaxLengthCleaner;

/**
 * Test cases for class MaxLengthCleaner.
 */
class MaxLengthCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner()
  {
    return new MaxLengthCleaner(10);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests within maximum length.
   */
  public function testClean1()
  {
    $cleaner = new MaxLengthCleaner(8);
    $raw     = 'hot';
    $value   = $cleaner->clean($raw);
    self::assertEquals($raw, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests equal to maximum length.
   */
  public function testClean2()
  {
    $cleaner = new MaxLengthCleaner(8);
    $raw     = 'buttered';
    $value   = $cleaner->clean($raw);
    self::assertEquals($raw, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests exceeding maximum length.
   */
  public function testClean3()
  {
    $cleaner = new MaxLengthCleaner(10);
    $raw     = 'Hyperbolicsyllabicsesquedalymistic';
    $value   = $cleaner->clean($raw);
    self::assertEquals('Hyperbolic', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
