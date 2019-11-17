<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\Cleaner;
use Plaisio\Form\Cleaner\TrimWhitespaceCleaner;

/**
 * Test cases for class TrimWhitespaceCleaner.
 */
class TrimWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner(): Cleaner
  {
    return TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean(): void
  {
    $raw     = "  Hello  World!   ";
    $cleaner = TrimWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('Hello  World!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

