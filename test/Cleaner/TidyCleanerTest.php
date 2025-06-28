<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use PHPUnit\Framework\Attributes\DataProvider;
use Plaisio\Form\Cleaner\TidyCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\Plaisio\PlaisioTestCase;

/**
 * Test cases for class TidyCleaner.
 */
class TidyCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return HTML snippet test cases.
   *
   * @return string[][]
   */
  public static function snippets(): array
  {
    return [['<h2>subheading</h3>', '<h2>subheading</h2>'],
            ['<h1>heading', '<h1>heading</h1>'],
            ['<br>', '<br />'],
            ['<p>&nbsp;</p>', null]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an instance of TidyCleaner.
   *
   * @return TidyCleaner
   */
  public function createCleaner(): TidyCleaner
  {
    return TidyCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with HTML snippet.
   *
   * @param string|null $value    The dirty value.
   * @param string|null $expected The expected clean value.
   */
  #[DataProvider('snippets')]
  public function testHtmlSnippet(?string $value, ?string $expected): void
  {
    $cleaner = TidyCleaner::get();

    $clean = $cleaner->clean($value);
    self::assertSame($expected, $clean, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

