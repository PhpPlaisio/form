<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner\Traits;

/**
 * Trait for cleaner that expect string values only.
 */
trait StringCleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns non-strings.
   *
   * @return array
   */
  public function getNonStrings(): array
  {
    return [[[]],
            [$this],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a cleaning a non-string yields the item.
   *
   * @param mixed $value The non-string.
   *
   * @dataProvider getNonStrings
   */
  public function testNonString($value): void
  {
    $this->expectException(\LogicException::class);

    $cleaner = $this->createCleaner();
    $cleaner->clean($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a cleaning null yields null.
   */
  public function testNull(): void
  {
    $cleaner = $this->createCleaner();
    $clean   = $cleaner->clean('');
    self::assertNull($clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a cleaning an empty string yields null.
   */
  public function testSpace(): void
  {
    $cleaner = $this->createCleaner();
    $clean   = $cleaner->clean('');
    self::assertNull($clean);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
