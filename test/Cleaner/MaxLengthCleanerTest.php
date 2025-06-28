<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\MaxLengthCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Test cases for class MaxLengthCleaner.
 */
class MaxLengthCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of MaxLengthCleaner.
   *
   * @return MaxLengthCleaner
   */
  public function createCleaner(): MaxLengthCleaner
  {
    return new MaxLengthCleaner(8);
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
