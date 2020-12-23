<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\TidyCleaner;
use Plaisio\Form\Test\Cleaner\Traits\StringCleaner;
use Plaisio\Form\Test\PlaisioTestCase;

/**
 * Test cases for class TidyCleaner.
 */
class TidyCleanerTest extends PlaisioTestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  use StringCleaner;

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
   * Return HTML snippet test cases.
   *
   * @return string[][]
   */
  public function snippets(): array
  {
    return [['<h2>subheading</h3>', '<h2>subheading</h2>'],
            ['<h1>heading', '<h1>heading</h1>'],
            ['<br>', '<br />'],
            ['<p>&nbsp;</p>', null]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with HTML snippet.
   *
   * @dataProvider snippets
   *
   * @param string|null $value    The dirty value.
   * @param string|null $expected The expected clean value.
   */
  public function testHtmlSnippet(?string $value, ?string $expected): void
  {
    $cleaner = TidyCleaner::get();

    $clean = $cleaner->clean($value);
    self::assertSame($expected, $clean, $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

