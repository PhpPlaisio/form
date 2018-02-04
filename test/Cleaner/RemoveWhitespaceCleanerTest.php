<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Test\Cleaner;

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
  public function makeCleaner()
  {
    return RemoveWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testClean()
  {
    $raw     = "  Hello  \n\n  World!   ";
    $cleaner = RemoveWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('HelloWorld!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

