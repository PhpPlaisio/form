<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Cleaner;

//----------------------------------------------------------------------------------------------------------------------
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
   * @var AmbiguityCleaner
   */
  static private $singleton;

  /**
   * Intended entered characters (unambiguities) and there possible mistakenly entered characters (ambiguities).
   *
   * Characters marked with * we have actually encountered in other databases.
   *
   * @var array<str,string[]>
   */
  protected $ambiguities =
    // Spaces.
    [' '  => ["\xa0",          // NO-BRAKE SPACE*
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
     ''   => ["\x00", // NULL*
              "\x01", // START OF HEADING
              "\x02", // START OF TEXT
              "\x03", // END OF TEXT
              "\x04", // END OF TRANSMISSION
              "\x05", // ENQUIRY
              "\x06", // ACKNOWLEDGE
              "\x07", // BELL
              "\x08", // BACKSPACE
              "\x0c", // FORM FEED (FF)*, \f
              "\x0d", // CARRIAGE RETURN (CR)*, \r
              "\x0e", // SHIFT OUT
              "\x0f", // SHIFT IN
              "\x10", // DATA LINK ESCAPE
              "\x11", // DEVICE CONTROL ONE
              "\x12", // DEVICE CONTROL TWO
              "\x13", // DEVICE CONTROL THREE
              "\x14", // DEVICE CONTROL FOUR
              "\x15", // NEGATIVE ACKNOWLEDGE
              "\x16", // SYNCHRONOUS IDLE
              "\x17", // END OF TRANSMISSION BLOCK
              "\x18", // CANCEL
              "\x19", // END OF MEDIUM
              "\x1a", // SUBSTITUTE
              "\x1b", // ESCAPE*, \e
              "\x1c", // INFORMATION SEPARATOR FOUR
              "\x1d", // INFORMATION SEPARATOR THREE
              "\x1e", // INFORMATION SEPARATOR TWO
              "\x1f", // INFORMATION SEPARATOR ONE
              "\x7f", // DELETE
              "\x80", // <control>
              "\x81", // <control> 	
              "\x82", // BREAK PERMITTED HERE
              "\x83", // NO BREAK HERE
              "\x84", // <control>
              "\x85", // NEXT LINE (NEL)
              "\x86", // START OF SELECTED AREA
              "\x87", // END OF SELECTED AREA
              "\x88", // CHARACTER TABULATION SET
              "\x89", // CHARACTER TABULATION WITH JUSTIFICATION
              "\x8a", // LINE TABULATION SET
              "\x8b", // PARTIAL LINE FORWARD
              "\x8c", // PARTIAL LINE BACKWARD
              "\x8d", // REVERSE LINE FEED
              "\x8e", // SINGLE SHIFT TWO
              "\x8f", // SINGLE SHIFT THREE
              "\x90", // DEVICE CONTROL STRING 	
              "\x91", // PRIVATE USE ONE
              "\x92", // PRIVATE USE TWO
              "\x93", // SET TRANSMIT STATE
              "\x94", // CANCEL CHARACTER
              "\x95", // MESSAGE WAITING
              "\x96", // START OF GUARDED AREA
              "\x97", // END OF GUARDED AREA
              "\x98", // START OF STRING
              "\x99", // <control>
              "\x9a", // SINGLE CHARACTER INTRODUCER
              "\x9b", // CONTROL SEQUENCE INTRODUCER
              "\x9c", // STRING TERMINATOR
              "\x9d", // OPERATING SYSTEM COMMAND
              "\x9e", // PRIVACY MESSAGE
              "\x9f"] // APPLICATION PROGRAM COMMAND
    ];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the singleton instance of this class.
   *
   * @return AmbiguityCleaner
   */
  public static function get()
  {
    if (!self::$singleton) self::$singleton = new self();

    return self::$singleton;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Replaces all ambiguous characters in a submitted values with the intended characters.
   *
   * @param string|null $value The submitted value.
   *
   * @return string|null
   */
  public function clean($value)
  {
    // Return null for empty strings.
    if ($value==='' || $value===null || $value===false)
    {
      return null;
    }

    // Replace all ambiguous characters.
    $tmp = $value;
    foreach ($this->ambiguities as $unambiguity => $ambiguity)
    {
      // Note: str_replace works fine with multi byte characters. 
      $tmp = str_replace($ambiguity, $unambiguity, $tmp);
    }
    
    // Restore EOL for DOS users.
    if (PHP_EOL!="\n") $tmp = str_replace("\n", PHP_EOL, $tmp);

    // Return null for empty strings.
    if ($tmp==='') $tmp = null;

    return $tmp;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
