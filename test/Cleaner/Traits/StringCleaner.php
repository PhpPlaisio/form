<?php
declare(strict_types=1);

namespace Plaisio\Form\Test\Cleaner\Traits;

use PHPUnit\Framework\Attributes\DataProvider;

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
  public static function getNonStrings(): array
  {
    return [[[]],
            [new \stdClass()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test a cleaning a non-string yields the item.
   *
   * @param mixed $value The non-string.
   */
  #[DataProvider('getNonStrings')]
  public function testNonString(mixed $value): void
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
