<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner;

use Plaisio\Form\Cleaner\Cleaner;
use Plaisio\Form\Cleaner\TidyCleaner;

/**
 * Test cases for class TidyCleaner.
 */
class TidyCleanerTest extends CleanerTest
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function makeCleaner(): Cleaner
  {
    return TidyCleaner::get();
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function test01(): void
  {
    $cases                        = [];
    $cases['<h2>subheading</h3>'] = '<h2>subheading</h2>';
    $cases['<h1>heading']         = '<h1>heading</h1>';
    $cases['<br>']                = '<br />';

    $cleaner = TidyCleaner::get();

    foreach ($cases as $dirty => $clean)
    {
      $cleaned = $cleaner->clean($dirty);
      self::assertSame($clean, $cleaned, $dirty);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

