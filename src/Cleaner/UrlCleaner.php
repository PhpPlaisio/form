<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use Plaisio\Helper\Url;
use SetBased\Exception\LogicException;

/**
 * Cleaner for normalizing URLs.
 */
class UrlCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var UrlCleaner|null
   */
  private static ?UrlCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return UrlCleaner
   */
  public static function get(): UrlCleaner
  {
    if (self::$singleton===null)
    {
      self::$singleton = new self();
    }

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a normalized URL if the submitted value is a URL. Otherwise, returns the submitted value.
   *
   * @param mixed $value The submitted URL.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public function clean(mixed $value): ?string
  {
    if ($value==='' || $value===null)
    {
      return null;
    }

    if (!is_string($value))
    {
      throw new LogicException('Expecting a string, got a %s.', gettype($value));
    }

    $parts = parse_url($value);
    if (!is_array($parts))
    {
      return $value;
    }

    return Url::unParseUrl($parts, 'http');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
