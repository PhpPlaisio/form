<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Cleaner\DatabaseLabelCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;

/**
 * Testcases for class DatabaseLabelCleaner.
 */
class DatabaseLabelCleanerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of DatabaseLabelCleaner.
   *
   * @return DatabaseLabelCleaner
   */
  public function createCleaner(): DatabaseLabelCleaner
  {
    return new DatabaseLabelCleaner('CMP_ID');
  }


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
