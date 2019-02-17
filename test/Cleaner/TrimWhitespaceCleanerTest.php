<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Cleaner\TrimWhitespaceCleaner;

/**
 * Test cases for class TrimWhitespaceCleaner.
 */
class TrimWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner():Cleaner
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

