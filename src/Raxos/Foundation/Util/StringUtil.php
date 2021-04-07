<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use function array_map;
use function array_pop;
use function count;
use function explode;
use function implode;
use function join;
use function mb_strtolower;
use function mb_substr;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function preg_split;
use function strtolower;
use function transliterator_transliterate;
use function trim;

/**
 * Class StringUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class StringUtil
{

    /**
     * Glues the strings together with commas and replaces the last one with
     * an amperstand.
     *
     * @param string[] $strings
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function commaCommaAnd(array $strings): string
    {
        return preg_replace('/(.*),/', '$1 &', implode(', ', $strings));
    }

    /**
     * Returns TRUE if the given string is serialized data.
     *
     * @param string $data
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function isSerialized(string $data): bool
    {
        $data = trim($data);

        if ('N;' === $data)
            return true;

        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;

        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;

            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * Implementation of substr_replace with multibyte support.
     *
     * @param string $str
     * @param string $replacement
     * @param int $start
     * @param int|null $length
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function multiByteSubstringReplace(string $str, string $replacement, int $start, ?int $length = null): string
    {
        $before = mb_substr($str, 0, $start);
        $after = mb_substr($str, $start + ($length ?? 0));

        return $before . $replacement . $after;
    }

    /**
     * Slugifies a string.
     *
     * @param string $str
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function slugify(string $str): string
    {
        $str = preg_replace('~[^\pL\d]+~u', '-', $str);
        $str = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $str);
        $str = preg_replace('~[^-\w]+~', '', $str);
        $str = trim($str, '-');
        $str = preg_replace('~-+~', '-', $str);
        $str = mb_strtolower($str);

        return !empty($str) ? $str : '';
    }

    /**
     * Splits text into sentences.
     *
     * @param string $str
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function splitSentences(string $str): array
    {
        return preg_split('/(?<!\.\.\.)(?<!Dr\.)(?<=[.?!]|\.\)|\.")\s+(?=[a-zA-Z"(])/', $str);
    }

    /**
     * Converts the given string to pascal case.
     *
     * @param string $str
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function toPascalCase(string $str): string
    {
        preg_match_all('/([a-zA-Z0-9]+)/', $str, $matches);

        return join(array_map('ucfirst', $matches[0]));
    }

    /**
     * Converts the given string to snake case.
     *
     * @param string $str
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function toSnakeCase(string $str): string
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $str));
    }

    /**
     * Creates an excerpt.
     *
     * @param string $text
     * @param int $wordCount
     * @param string $ending
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function truncateText(string $text, int $wordCount = 20, string $ending = '&hellip;'): string
    {
        $excerpt = $text;
        $excerpt = preg_replace("/<h2>.+?<\/h2>/i", "", $excerpt);
        $excerpt = preg_replace("/<h3>.+?<\/h3>/i", "", $excerpt);
        $words = explode(' ', $excerpt, $wordCount + 1);

        if (count($words) > $wordCount) {
            array_pop($words);
            $excerpt = implode(' ', $words);
            $excerpt .= $ending;
        }

        return trim($excerpt);
    }

}
