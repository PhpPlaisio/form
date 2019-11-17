<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\Cleaner;
use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;

/**
 * Test cases for class PruneWhitespaceCleaner.
 */
class PruneWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner(): Cleaner
  {
    return PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean(): void
  {
    $raw     = "  Hello  \n\n\x0a\x00  World!   \x0b";
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('Hello World!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

