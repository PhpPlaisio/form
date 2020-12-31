<?php
declare(strict_types=1);

namespace Plaisio\Form\Cleaner;

use SetBased\Exception\LogicException;

/**
 * Cleaner for replacing ambiguous characters.
 *
 * Users can enter by mistake (in most cases by copy-paste from other sources) or by malicious input characters that
 * are different from the character that was intended to be entered but look very similar or that are not visible at
 * all. For example SPACE (U+0020) and NO-BRAKE SPACE (U+00A0) a.k.a. nbsp. If those characters end up in the database
 * they can be the root cause of many data issues.
 *
 * This cleaner will replace common mistakenly entered characters with the intended entered character.
 *
 * In unicode there are many other characters that look similar, COLON (U+003A) and FULLWIDTH COLON' (U+FF1A), and
 * many other characters from different blocks. Handling these characters is better handled by Validators.
 */
class AmbiguityCleaner implements Cleaner
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The singleton instance of this class.
   *
   * @var AmbiguityCleaner|null
   */
  static private ?AmbiguityCleaner $singleton = null;

  /**
   * Intended entered characters (unambiguities) and there possible mistakenly entered characters (ambiguities).
   *
   * Characters marked with * we have actually encountered in other databases.
   *
   * @var array[]
   */
  protected array $ambiguities =
    // Spaces.
    [' '  => ["\xc2\xa0",      // NO-BRAKE SPACE*
              "\xe1\x9a\x80",  // OGHAM SPACE MARK*
              "\xe1\xa0\x8e",  // MONGOLIAN VOWEL SEPARATOR
              "\xe2\x80\x80",  // EN QUAD
              "\xe2\x80\x81",  // EM QUAD
              "\xe2\x80\x82",  // EN SPACE
              "\xe2\x80\x83",  // EM SPACE
              "\xe2\x80\x84",  // THREE-PER-EM SPACE
              "\xe2\x80\x85",  // FOUR-PER-EM SPACE
              "\xe2\x80\x86",  // SIX-PER-EM SPACE
              "\xe2\x80\x87",  // FIGURE SPACE
              "\xe2\x80\x88",  // PUNCTUATION SPACE
              "\xe2\x80\x89",  // THIN SPACE
              "\xe2\x80\x8a",  // HAIR SPACE
              "\xe2\x80\x8b",  // ZERO WIDTH SPACE
              "\xe2\x80\x8c",  // ZERO WIDTH NON-JOINER
              "\xe2\x80\x8d",  // ZERO WIDTH JOINER
              "\xe2\x80\x8f",  // NARROW NO-BREAK SPACE
              "\xe2\x81\x9f",  // MEDIUM MATHEMATICAL SPACE
              "\xe2\x81\xa0",  // WORD JOINER
              "\xe2\x81\xa1",  // FUNCTION APPLICATION
              "\xe2\x81\xa2",  // INVISIBLE TIMES
              "\xe2\x81\xa3",  // INVISIBLE SEPARATOR
              "\xe3\x80\x80",  // IDEOGRAPHIC SPACE
              "\xef\xbb\xbf"], // ZERO WIDTH NO-BREAK SPACE

     // New lines.
     "\n" => ["\x0b",          // LINE TABULATION*, \v
              "\xe2\x80\xa8",  // LINE SEPARATOR
              "\xe2\x80\xa9"], // PARAGRAPH SEPARATOR

     // Control characters.
     ''   => ["\x00",     // NULL*
              "\x01",     // START OF HEADING
              "\x02",     // START OF TEXT
              "\x03",     // END OF TEXT
              "\x04",     // END OF TRANSMISSION
              "\x05",     // ENQUIRY
              "\x06",     // ACKNOWLEDGE
              "\x07",     // BELL
              "\x08",     // BACKSPACE
              "\x0c",     // FORM FEED (FF)*, \f
              "\x0d",     // CARRIAGE RETURN (CR)*, \r
              "\x0e",     // SHIFT OUT
              "\x0f",     // SHIFT IN
              "\x10",     // DATA LINK ESCAPE
              "\x11",     // DEVICE CONTROL ONE
              "\x12",     // DEVICE CONTROL TWO
              "\x13",     // DEVICE CONTROL THREE
              "\x14",     // DEVICE CONTROL FOUR
              "\x15",     // NEGATIVE ACKNOWLEDGE
              "\x16",     // SYNCHRONOUS IDLE
              "\x17",     // END OF TRANSMISSION BLOCK
              "\x18",     // CANCEL
              "\x19",     // END OF MEDIUM
              "\x1a",     // SUBSTITUTE
              "\x1b",     // ESCAPE*, \e
              "\x1c",     // INFORMATION SEPARATOR FOUR
              "\x1d",     // INFORMATION SEPARATOR THREE
              "\x1e",     // INFORMATION SEPARATOR TWO
              "\x1f",     // INFORMATION SEPARATOR ONE
              "\x7f",     // DELETE
              "\xc2\x80", // <control>
              "\xc2\x81", // <control> 	
              "\xc2\x82", // BREAK PERMITTED HERE
              "\xc2\x83", // NO BREAK HERE
              "\xc2\x84", // <control>
              "\xc2\x85", // NEXT LINE (NEL)
              "\xc2\x86", // START OF SELECTED AREA
              "\xc2\x87", // END OF SELECTED AREA
              "\xc2\x88", // CHARACTER TABULATION SET
              "\xc2\x89", // CHARACTER TABULATION WITH JUSTIFICATION
              "\xc2\x8a", // LINE TABULATION SET
              "\xc2\x8b", // PARTIAL LINE FORWARD
              "\xc2\x8c", // PARTIAL LINE BACKWARD
              "\xc2\x8d", // REVERSE LINE FEED
              "\xc2\x8e", // SINGLE SHIFT TWO
              "\xc2\x8f", // SINGLE SHIFT THREE
              "\xc2\x90", // DEVICE CONTROL STRING 	
              "\xc2\x91", // PRIVATE USE ONE
              "\xc2\x92", // PRIVATE USE TWO
              "\xc2\x93", // SET TRANSMIT STATE
              "\xc2\x94", // CANCEL CHARACTER
              "\xc2\x95", // MESSAGE WAITING
              "\xc2\x96", // START OF GUARDED AREA
              "\xc2\x97", // END OF GUARDED AREA
              "\xc2\x98", // START OF STRING
              "\xc2\x99", // <control>
              "\xc2\x9a", // SINGLE CHARACTER INTRODUCER
              "\xc2\x9b", // CONTROL SEQUENCE INTRODUCER
              "\xc2\x9c", // STRING TERMINATOR
              "\xc2\x9d", // OPERATING SYSTEM COMMAND
              "\xc2\x9e", // PRIVACY MESSAGE
              "\xc2\x9f"] // APPLICATION PROGRAM COMMAND
    ];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return AmbiguityCleaner
   */
  public static function get(): AmbiguityCleaner
  {
    if (self::$singleton===null) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Replaces all ambiguous characters in a submitted value with the intended characters.
   *
   * @param mixed $value The submitted value.
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

    if (!is_string($value))
    {
      throw new LogicException('Expecting a string, got a %s.', gettype($value));
    }

    // Replace all ambiguous characters.
    $clean = $value;
    foreach ($this->ambiguities as $unambiguity => $ambiguities)
    {
      foreach ($ambiguities as $ambiguity)
      {
        // Note: str_replace works fine with multi byte characters like UTF-8.
        $clean = str_replace($ambiguity, $unambiguity, $clean);
      }
    }

    // Restore EOL for DOS users.
    if (PHP_EOL!="\n")
    {
      $clean = str_replace("\n", PHP_EOL, $clean);
    }

    return ($clean==='') ? null : $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
