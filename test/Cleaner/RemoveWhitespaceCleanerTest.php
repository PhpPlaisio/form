<?php
declare(strict_types=1);

namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\Cleaner;
use SetBased\Abc\Form\Cleaner\RemoveWhitespaceCleaner;

/**
 * Test cases for class RemoveWhitespaceCleaner.
 */
class RemoveWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner():Cleaner
  {
    return RemoveWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean(): void
  {
    $raw     = "  Hello  \n\n  World!   ";
    $cleaner = RemoveWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('HelloWorld!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

