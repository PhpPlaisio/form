<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use Plaisio\Helper\Html;
use SetBased\Exception\FallenException;

/**
 * Cleaner for cleaning HTML code using [HTML Tidy](http://www.html-tidy.org/).
 */
class TidyCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var TidyCleaner|null
   */
  static private ?TidyCleaner $singleton = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return TidyCleaner
   */
  public static function get(): TidyCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the Tidy's encoding name of the PHP encoding as set in Html::$encoding.
   *
   * @return string
   */
  private static function getTidyEncoding(): string
  {
    switch (Html::$encoding)
    {
      case 'UTF-8':
        return 'utf8';

      case 'ISO8859-1':
      case 'ISO-8859-1':
        return 'latin1';

      case 'cp1251':
      case 'Windows-1251':
      case 'win-1251':
      case '1251':
        return 'win1252';

      case 'BIG5-HKSCS':
        return 'big5';

      case 'Shift_JIS':
      case 'SJIS':
      case 'SJIS-win':
      case 'cp932':
      case '932':
        return 'shiftjis';

      case 'MacRoman':
        return 'mac';

      default:
        throw new FallenException('encoding', self::getTidyEncoding());
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a HTML snippet cleaned by [HTML Tidy](http://www.html-tidy.org/).
   *
   * @param mixed $value The submitted HTML snippet.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  public function clean($value)
  {
    // First prune whitespace.
    $cleaner = PruneWhitespaceCleaner::get();
    $value   = $cleaner->clean($value);

    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    $tidyConfig = ['clean'          => false,
                   'output-xhtml'   => true,
                   'show-body-only' => true,
                   'wrap'           => 100];

    $tidy = new \tidy;

    $tidy->parseString($value, $tidyConfig, self::getTidyEncoding());
    $tidy->cleanRepair();
    $value = trim(tidy_get_output($tidy));

    // In some cases Tidy returns an empty paragraph only.
    if (preg_match('/^(([ \r\n\t])|(<p>)|(<\/p>)|(&nbsp;))*$/', $value)==1)
    {
      $value = null;
    }

    return $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
