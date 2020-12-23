<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use Plaisio\Helper\Url;

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
  static private ?UrlCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return UrlCleaner
   */
  public static function get(): UrlCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a normalized URL if the submitted value is a URL. Otherwise returns the submitted value.
   *
   * @param mixed $value The submitted URL.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  public function clean($value)
  {
    // Return null for empty strings.
    if ($value==='' || $value===null)
    {
      return null;
    }

    // Return original value for non-strings.
    if (!is_string($value))
    {
      return $value;
    }

    // Split the URL in parts.
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
