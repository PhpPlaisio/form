<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Control;

use PHPUnit\Framework\TestCase;
use Plaisio\Form\Control\DatabaseLabelControl;

/**
 * Test cases for class LabelControl.
 */
class DatabaseLabelControlTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid label prefixes.
   */
  public function getInvalidPrefixes(): array
  {
    return [['123ABC'],
            ['_CMP_ID'],
            ['cmp_id'],
            ['CMP_ID_']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test invalid prefix.
   *
   * @param string $prefix The invalid prefix.
   *
   * @dataProvider getInvalidPrefixes
   */
  public function testInvalidPrefix1(string $prefix): void
  {
    $this->expectException(\LogicException::class);
    new DatabaseLabelControl('label', $prefix, 10);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test valid prefix.
   */
  public function testValidPrefix(): void
  {
    $control = new DatabaseLabelControl('label', 'CMP_ID', 10);
    self::assertNotNull($control);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
