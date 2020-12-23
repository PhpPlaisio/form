<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\RemoveWhitespaceCleaner;
use Plaisio\Form\Cleaner\TrimWhitespaceCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Test cases for class RemoveWhitespaceCleaner.
 */
class RemoveWhitespaceCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of RemoveWhitespaceCleaner.
   *
   * @return RemoveWhitespaceCleaner
   */
  public function createCleaner(): RemoveWhitespaceCleaner
  {
    return RemoveWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with non-empty string.
   */
  public function testClean(): void
  {
    $raw     = "  Hello  \n\n  World!   ";
    $cleaner = RemoveWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('HelloWorld!', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests whitespaces only.
   *
   * @dataProvider whitespaceOnly
   *
   * @param string|null $string $string String with only whitespace.
   */
  public function testWhitSpaceOnly(?string $string): void
  {
    $cleaner = TrimWhitespaceCleaner::get();
    $clean   = $cleaner->clean($string);

    self::assertNull($clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns cases with with spaces only.
   *
   * @return array
   */
  public function whitespaceOnly(): array
  {
    return [[''], [null], [' '], ['  '], ["\n"], ["\n \n"], ["\n \t"], [" \t\n\r\0\x0B \t\n\r\0\x0B"]];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

