<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\PruneWhitespaceCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Test cases for class PruneWhitespaceCleaner.
 */
class PruneWhitespaceCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns cases with spaces only.
   *
   * @return array
   */
  public static function whitespaceOnly(): array
  {
    return [[''], [null], [' '], ['  '], ["\n"], ["\n \n"], ["\n \t"], [" \t\n\r\0\x0B \t\n\r\0\x0B"]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of PruneWhitespaceCleaner.
   *
   * @return PruneWhitespaceCleaner
   */
  public function createCleaner(): PruneWhitespaceCleaner
  {
    return PruneWhitespaceCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple with space.
   */
  public function testClean(): void
  {
    $raw     = "  Hello  \n\n\x0a\x00  World!   \x0b";
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertEquals('Hello World!', $value);
  }


  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with ambiguous whitespaceOnly only.
   */
  public function testEmpty(): void
  {
    $raw     = "\x00";
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($raw);

    self::assertNull($value);
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
    $cleaner = PruneWhitespaceCleaner::get();
    $clean   = $cleaner->clean($string);

    self::assertNull($clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

