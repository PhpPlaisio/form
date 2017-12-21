<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

use SetBased\Abc\Form\Cleaner\TrimWhitespaceCleaner;

/**
 * Test cases for class TrimWhitespaceCleaner.
 */
class TrimWhitespaceCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function makeCleaner()
  {
    return TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean()
  {
    $raw     = "  Hello  World!   ";
    $cleaner = TrimWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('Hello  World!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

