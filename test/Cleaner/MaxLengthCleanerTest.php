<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\Cleaner;
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
  public function makeCleaner(): Cleaner
  {
    return new MaxLengthCleaner(10);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests within maximum length.
   */
  public function testClean1(): void
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
  public function testClean2(): void
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
  public function testClean3(): void
  {
    $cleaner = new MaxLengthCleaner(10);
    $raw     = 'Hyperbolicsyllabicsesquedalymistic';
    $value   = $cleaner->clean($raw);
    self::assertEquals('Hyperbolic', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
