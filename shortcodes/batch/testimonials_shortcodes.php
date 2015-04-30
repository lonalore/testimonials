<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Class installation to define shortcodes.
 */

if(!defined('e107_INIT'))
{
	exit;
}

/**
 * Matches Unicode characters that are word boundaries.
 *
 * Characters with the following General_category (gc) property values are used
 * as word boundaries. While this does not fully conform to the Word Boundaries
 * algorithm described in http://unicode.org/reports/tr29, as PCRE does not
 * contain the Word_Break property table, this simpler algorithm has to do.
 * - Cc, Cf, Cn, Co, Cs: Other.
 * - Pc, Pd, Pe, Pf, Pi, Po, Ps: Punctuation.
 * - Sc, Sk, Sm, So: Symbols.
 * - Zl, Zp, Zs: Separators.
 *
 * Non-boundary characters include the following General_category (gc) property
 * values:
 * - Ll, Lm, Lo, Lt, Lu: Letters.
 * - Mc, Me, Mn: Combining Marks.
 * - Nd, Nl, No: Numbers.
 *
 * Note that the PCRE property matcher is not used because we wanted to be
 * compatible with Unicode 5.2.0 regardless of the PCRE version used (and any
 * bugs in PCRE property tables).
 *
 * @see http://unicode.org/glossary
 */
define('TM_PREG_CLASS_UNICODE_WORD_BOUNDARY',
	'\x{0}-\x{2F}\x{3A}-\x{40}\x{5B}-\x{60}\x{7B}-\x{A9}\x{AB}-\x{B1}\x{B4}' .
	'\x{B6}-\x{B8}\x{BB}\x{BF}\x{D7}\x{F7}\x{2C2}-\x{2C5}\x{2D2}-\x{2DF}' .
	'\x{2E5}-\x{2EB}\x{2ED}\x{2EF}-\x{2FF}\x{375}\x{37E}-\x{385}\x{387}\x{3F6}' .
	'\x{482}\x{55A}-\x{55F}\x{589}-\x{58A}\x{5BE}\x{5C0}\x{5C3}\x{5C6}' .
	'\x{5F3}-\x{60F}\x{61B}-\x{61F}\x{66A}-\x{66D}\x{6D4}\x{6DD}\x{6E9}' .
	'\x{6FD}-\x{6FE}\x{700}-\x{70F}\x{7F6}-\x{7F9}\x{830}-\x{83E}' .
	'\x{964}-\x{965}\x{970}\x{9F2}-\x{9F3}\x{9FA}-\x{9FB}\x{AF1}\x{B70}' .
	'\x{BF3}-\x{BFA}\x{C7F}\x{CF1}-\x{CF2}\x{D79}\x{DF4}\x{E3F}\x{E4F}' .
	'\x{E5A}-\x{E5B}\x{F01}-\x{F17}\x{F1A}-\x{F1F}\x{F34}\x{F36}\x{F38}' .
	'\x{F3A}-\x{F3D}\x{F85}\x{FBE}-\x{FC5}\x{FC7}-\x{FD8}\x{104A}-\x{104F}' .
	'\x{109E}-\x{109F}\x{10FB}\x{1360}-\x{1368}\x{1390}-\x{1399}\x{1400}' .
	'\x{166D}-\x{166E}\x{1680}\x{169B}-\x{169C}\x{16EB}-\x{16ED}' .
	'\x{1735}-\x{1736}\x{17B4}-\x{17B5}\x{17D4}-\x{17D6}\x{17D8}-\x{17DB}' .
	'\x{1800}-\x{180A}\x{180E}\x{1940}-\x{1945}\x{19DE}-\x{19FF}' .
	'\x{1A1E}-\x{1A1F}\x{1AA0}-\x{1AA6}\x{1AA8}-\x{1AAD}\x{1B5A}-\x{1B6A}' .
	'\x{1B74}-\x{1B7C}\x{1C3B}-\x{1C3F}\x{1C7E}-\x{1C7F}\x{1CD3}\x{1FBD}' .
	'\x{1FBF}-\x{1FC1}\x{1FCD}-\x{1FCF}\x{1FDD}-\x{1FDF}\x{1FED}-\x{1FEF}' .
	'\x{1FFD}-\x{206F}\x{207A}-\x{207E}\x{208A}-\x{208E}\x{20A0}-\x{20B8}' .
	'\x{2100}-\x{2101}\x{2103}-\x{2106}\x{2108}-\x{2109}\x{2114}' .
	'\x{2116}-\x{2118}\x{211E}-\x{2123}\x{2125}\x{2127}\x{2129}\x{212E}' .
	'\x{213A}-\x{213B}\x{2140}-\x{2144}\x{214A}-\x{214D}\x{214F}' .
	'\x{2190}-\x{244A}\x{249C}-\x{24E9}\x{2500}-\x{2775}\x{2794}-\x{2B59}' .
	'\x{2CE5}-\x{2CEA}\x{2CF9}-\x{2CFC}\x{2CFE}-\x{2CFF}\x{2E00}-\x{2E2E}' .
	'\x{2E30}-\x{3004}\x{3008}-\x{3020}\x{3030}\x{3036}-\x{3037}' .
	'\x{303D}-\x{303F}\x{309B}-\x{309C}\x{30A0}\x{30FB}\x{3190}-\x{3191}' .
	'\x{3196}-\x{319F}\x{31C0}-\x{31E3}\x{3200}-\x{321E}\x{322A}-\x{3250}' .
	'\x{3260}-\x{327F}\x{328A}-\x{32B0}\x{32C0}-\x{33FF}\x{4DC0}-\x{4DFF}' .
	'\x{A490}-\x{A4C6}\x{A4FE}-\x{A4FF}\x{A60D}-\x{A60F}\x{A673}\x{A67E}' .
	'\x{A6F2}-\x{A716}\x{A720}-\x{A721}\x{A789}-\x{A78A}\x{A828}-\x{A82B}' .
	'\x{A836}-\x{A839}\x{A874}-\x{A877}\x{A8CE}-\x{A8CF}\x{A8F8}-\x{A8FA}' .
	'\x{A92E}-\x{A92F}\x{A95F}\x{A9C1}-\x{A9CD}\x{A9DE}-\x{A9DF}' .
	'\x{AA5C}-\x{AA5F}\x{AA77}-\x{AA79}\x{AADE}-\x{AADF}\x{ABEB}' .
	'\x{E000}-\x{F8FF}\x{FB29}\x{FD3E}-\x{FD3F}\x{FDFC}-\x{FDFD}' .
	'\x{FE10}-\x{FE19}\x{FE30}-\x{FE6B}\x{FEFF}-\x{FF0F}\x{FF1A}-\x{FF20}' .
	'\x{FF3B}-\x{FF40}\x{FF5B}-\x{FF65}\x{FFE0}-\x{FFFD}');


/**
 * Class testimonials_shortcodes.
 */
class testimonials_shortcodes extends e_shortcode
{

	/**
	 * Private variable to store plugin configurations.
	 *
	 * @var array
	 */
	private $plugPrefs = array();


	/**
	 * Constructor.
	 */
	function __construct()
	{
		$this->plugPrefs = e107::getPlugConfig('testimonials')->getPref();
	}


	/**
	 * Bootstrap Carousel indicators.
	 *
	 * @return string
	 */
	function sc_testimonials_indicators()
	{
		$indicators = array();
		if(isset($this->var['count']) && (int) $this->var['count'] > 0)
		{
			for($i = 0; $i < (int) $this->var['count']; $i++)
			{
				$indicators[] = '<li data-target="#quote-carousel" data-slide-to="' . $i . '"' . ($i === 0 ? ' class="active"' : '') . '></li>';
			}
		}
		return '<ol class="carousel-indicators">' . implode('', $indicators) . '</ol>';
	}


	/**
	 * Returns an "active" css class for the first carousel item.
	 *
	 * @return string
	 */
	function sc_testimonials_active()
	{
		return ($this->var['active'] === true ? ' active' : '');
	}


	/**
	 * Testimonial message.
	 *
	 * @return mixed
	 */
	function sc_testimonials_message()
	{
		if(!isset($this->plugPrefs['tm_trim']) || (int) $this->plugPrefs['tm_trim'] === 0)
		{
			return $this->var['tm_message'];
		}
		else
		{
			$length = (int) $this->plugPrefs['tm_trim'];
			$trimmed = $this->_tm_truncate_utf8($this->var['tm_message'], $length, false, true);
			return $trimmed;
		}
	}


	/**
	 * Testimonial author. If testimonial item has an URL, it returns with a link,
	 * or return with a link if no URL but the author is a registered user,
	 * otherwise returns with the author's name without link.
	 *
	 * @return string
	 */
	function sc_testimonials_author()
	{
		if(!empty($this->var['tm_url']))
		{
			return '<a href="' . $this->var['tm_url'] . '" target="_blank">' . $this->var['user_name'] . '</a>';
		}

		$uid = (int) $this->var['user_id'];
		if($uid === 0)
		{
			return $this->var['user_name'];
		}

		// TODO: use e107::url() for SEF URL...
		return '<a href="' . e_HTTP . 'user.php?id.' . $uid . '">' . $this->var['user_name'] . '</a>';
	}


	/**
	 * Helper function to truncate a UTF-8-encoded string safely to a number of
	 * characters.
	 *
	 * @param $string
	 *   The string to truncate.
	 * @param $max_length
	 *   An upper limit on the returned string length, including trailing ellipsis
	 *   if $add_ellipsis is TRUE.
	 * @param $wordsafe
	 *   If TRUE, attempt to truncate on a word boundary. Word boundaries are
	 *   spaces, punctuation, and Unicode characters used as word boundaries in
	 *   non-Latin languages; see PREG_CLASS_UNICODE_WORD_BOUNDARY for more
	 *   information. If a word boundary cannot be found that would make the length
	 *   of the returned string fall within length guidelines (see parameters
	 *   $max_length and $min_wordsafe_length), word boundaries are ignored.
	 * @param $add_ellipsis
	 *   If TRUE, add t('...') to the end of the truncated string (defaults to
	 *   FALSE). The string length will still fall within $max_length.
	 * @param $min_wordsafe_length
	 *   If $wordsafe is TRUE, the minimum acceptable length for truncation (before
	 *   adding an ellipsis, if $add_ellipsis is TRUE). Has no effect if $wordsafe
	 *   is FALSE. This can be used to prevent having a very short resulting string
	 *   that will not be understandable. For instance, if you are truncating the
	 *   string "See myverylongurlexample.com for more information" to a word-safe
	 *   return length of 20, the only available word boundary within 20 characters
	 *   is after the word "See", which wouldn't leave a very informative string. If
	 *   you had set $min_wordsafe_length to 10, though, the function would realise
	 *   that "See" alone is too short, and would then just truncate ignoring word
	 *   boundaries, giving you "See myverylongurl..." (assuming you had set
	 *   $add_ellipses to TRUE).
	 *
	 * @return string
	 *   The truncated string.
	 */
	function _tm_truncate_utf8($string, $max_length, $wordsafe = false, $add_ellipsis = false, $min_wordsafe_length = 1)
	{
		$ellipsis = '';
		$max_length = max($max_length, 0);
		$min_wordsafe_length = max($min_wordsafe_length, 0);

		if($this->_tm_strlen($string) <= $max_length)
		{
			// No truncation needed, so don't add ellipsis, just return.
			return $string;
		}

		if($add_ellipsis)
		{
			// Truncate ellipsis in case $max_length is small.
			$ellipsis = $this->_tm_substr('...', 0, $max_length);
			$max_length -= $this->_tm_strlen($ellipsis);
			$max_length = max($max_length, 0);
		}

		if($max_length <= $min_wordsafe_length)
		{
			// Do not attempt word-safe if lengths are bad.
			$wordsafe = false;
		}

		if($wordsafe)
		{
			$matches = array();
			// Find the last word boundary, if there is one within $min_wordsafe_length
			// to $max_length characters. preg_match() is always greedy, so it will
			// find the longest string possible.
			$found = preg_match('/^(.{' . $min_wordsafe_length . ',' . $max_length . '})[' . TM_PREG_CLASS_UNICODE_WORD_BOUNDARY . ']/u', $string, $matches);
			if($found)
			{
				$string = $matches[1];
			}
			else
			{
				$string = $this->_tm_substr($string, 0, $max_length);
			}
		}
		else
		{
			$string = $this->_tm_substr($string, 0, $max_length);
		}

		if($add_ellipsis)
		{
			$string .= $ellipsis;
		}

		return $string;
	}


	/**
	 * Helper function to count the number of characters in a UTF-8 string.
	 *
	 * This is less than or equal to the byte count.
	 *
	 * @param $text
	 *   The string to run the operation on.
	 *
	 * @return integer
	 *   The length of the string.
	 *
	 * @ingroup php_wrappers
	 */
	function _tm_strlen($text)
	{
		// Do not count UTF-8 continuation bytes.
		return strlen(preg_replace("/[\x80-\xBF]/", '', $text));
	}


	/**
	 * Helper function to cut off a piece of a string based on character indices and
	 * counts.
	 *
	 * Follows the same behavior as PHP's own substr() function. Note that for
	 * cutting off a string at a known character/substring location, the usage of
	 * PHP's normal strpos/substr is safe and much faster.
	 *
	 * @param $text
	 *   The input string.
	 * @param $start
	 *   The position at which to start reading.
	 * @param $length
	 *   The number of characters to read.
	 *
	 * @return string
	 *   The shortened string.
	 *
	 * @ingroup php_wrappers
	 */
	function _tm_substr($text, $start, $length = null)
	{
		$strlen = strlen($text);
		// Find the starting byte offset.
		$bytes = 0;
		if($start > 0)
		{
			// Count all the continuation bytes from the start until we have found
			// $start characters or the end of the string.
			$bytes = -1;
			$chars = -1;
			while($bytes < $strlen - 1 && $chars < $start)
			{
				$bytes++;
				$c = ord($text[$bytes]);
				if($c < 0x80 || $c >= 0xC0)
				{
					$chars++;
				}
			}
		}
		elseif($start < 0)
		{
			// Count all the continuation bytes from the end until we have found
			// abs($start) characters.
			$start = abs($start);
			$bytes = $strlen;
			$chars = 0;
			while($bytes > 0 && $chars < $start)
			{
				$bytes--;
				$c = ord($text[$bytes]);
				if($c < 0x80 || $c >= 0xC0)
				{
					$chars++;
				}
			}
		}
		$istart = $bytes;

		// Find the ending byte offset.
		if($length === null)
		{
			$iend = $strlen;
		}
		elseif($length > 0)
		{
			// Count all the continuation bytes from the starting index until we have
			// found $length characters or reached the end of the string, then
			// backtrace one byte.
			$iend = $istart - 1;
			$chars = -1;
			$last_real = false;
			while($iend < $strlen - 1 && $chars < $length)
			{
				$iend++;
				$c = ord($text[$iend]);
				$last_real = false;
				if($c < 0x80 || $c >= 0xC0)
				{
					$chars++;
					$last_real = true;
				}
			}
			// Backtrace one byte if the last character we found was a real character
			// and we don't need it.
			if($last_real && $chars >= $length)
			{
				$iend--;
			}
		}
		elseif($length < 0)
		{
			// Count all the continuation bytes from the end until we have found
			// abs($start) characters, then backtrace one byte.
			$length = abs($length);
			$iend = $strlen;
			$chars = 0;
			while($iend > 0 && $chars < $length)
			{
				$iend--;
				$c = ord($text[$iend]);
				if($c < 0x80 || $c >= 0xC0)
				{
					$chars++;
				}
			}
			// Backtrace one byte if we are not at the beginning of the string.
			if($iend > 0)
			{
				$iend--;
			}
		}
		else
		{
			// $length == 0, return an empty string.
			return '';
		}

		return substr($text, $istart, max(0, $iend - $istart + 1));
	}
}
