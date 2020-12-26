<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Cleaner\DatabaseLabelCleaner;

/**
 * Testcases for class DatabaseLabelCleaner.
 */
class DatabaseLabelCleanerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test on empty values returns null.
   */
  public function testEmpty(): void
  {
    $cleaner = new DatabaseLabelCleaner('CMP_ID');

    self::assertNull($cleaner->clean(null));
    self::assertNull($cleaner->clean(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test non-string are returned as is.
   */
  public function testNonString(): void
  {
    $cleaner = new DatabaseLabelCleaner('CMP_ID');

    self::assertSame($this, $cleaner->clean($this));
    self::assertSame([], $cleaner->clean([]));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test label matches /^[0-9A-Z_]+$/.
   */
  public function testPattern(): void
  {
    $cleaner = new DatabaseLabelCleaner('CMP_ID');

    self::assertMatchesRegularExpression('/^[0-9A-Z_]+$/', $cleaner->clean('Hello, World!'));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
