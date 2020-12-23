<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\TrimWhitespaceCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Test cases for class TrimWhitespaceCleaner.
 */
class TrimWhitespaceCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of TrimWhitespaceCleaner.
   *
   * @return TrimWhitespaceCleaner
   */
  public function createCleaner(): TrimWhitespaceCleaner
  {
    return TrimWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with leading and trailing whitespaceOnly.
   */
  public function testClean(): void
  {
    $raw     = "  Hello  World!   ";
    $cleaner = TrimWhitespaceCleaner::get();
    $clean   = $cleaner->clean($raw);

    self::assertEquals('Hello  World!', $clean);
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

