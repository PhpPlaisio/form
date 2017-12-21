<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\PruneWhitespaceCleaner;

/**
 * Test cases for class PruneWhitespaceCleaner.
 */
class PruneWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function makeCleaner()
  {
    return PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean()
  {
    $raw     = "  Hello  \n\n\x0a\x00  World!   \x0b";
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('Hello World!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

