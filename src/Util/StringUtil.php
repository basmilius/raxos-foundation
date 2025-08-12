<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JetBrains\PhpStorm\Pure;
use function array_map;
use function array_pop;
use function array_rand;
use function count;
use function explode;
use function floor;
use function implode;
use function mb_strtolower;
use function mb_substr;
use function mb_trim;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function preg_split;
use function round;
use function sqrt;
use function str_contains;
use function str_shuffle;
use function str_split;
use function strip_tags;
use function strlen;
use function strrchr;
use function strtolower;
use function strtr;
use function substr;
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

    private const int FORMAT_BYTES_FACTOR = 1024;
    private const array FORMAT_BYTES_IEC = ['', 'Ki', 'Mi', 'Gi', 'Ti', 'Pi', 'Ei', 'Zi', 'Yi'];
    private const array FORMAT_BYTES_SI = ['', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];

    /**
     * Glues the strings together with commas and replaces the last one with
     * an ampersand.
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
     * Formats the given bytes or bits into a string representation.
     *
     * @param int $value
     * @param int $decimals
     * @param bool $siMode
     * @param bool $bits
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    #[Pure]
    public static function formatBytes(int $value, int $decimals = 2, bool $siMode = true, bool $bits = false): string
    {
        $suffixes = $siMode ? self::FORMAT_BYTES_SI : self::FORMAT_BYTES_IEC;

        if ($bits) {
            $value *= 8;
        }

        for ($i = 0, $length = count($suffixes); $i < $length - 1 && $value >= self::FORMAT_BYTES_FACTOR; ++$i) {
            $value /= self::FORMAT_BYTES_FACTOR;
        }

        return round($value, $decimals) . ' ' . $suffixes[$i] . ($bits ? 'b' : 'B');
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

        if ('N;' === $data) {
            return true;
        }

        if (!preg_match('/^([adOCbis]):/', $data, $badions)) {
            return false;
        }

        switch ($badions[1]) {
            case 'a' :
            case 'C' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:\d+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;

            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[\d.E-]+;\$/", $data)) {
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
     * Generates a random string based on the given options.
     *
     * @param int $length
     * @param bool $dashes
     * @param string $sets
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 2.0.0
     */
    public static function random(int $length = 9, bool $dashes = false, string $sets = 'luds'): string
    {
        $usedSets = [];

        if (str_contains($sets, 'l')) {
            $usedSets[] = 'abcdefghjkmnpqrstuvwxyz';
        }

        if (str_contains($sets, 'u')) {
            $usedSets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }

        if (str_contains($sets, 'd')) {
            $usedSets[] = '123456789';
        }

        if (str_contains($sets, 's')) {
            $usedSets[] = '!@#$%&*?';
        }

        $all = '';
        $str = '';

        foreach ($usedSets as $set) {
            $str .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);

        for ($i = 0, $count = count($usedSets); $i < $length - $count; ++$i) {
            $str .= $all[array_rand($all)];
        }

        $str = str_shuffle($str);

        if (!$dashes) {
            return $str;
        }

        $dashLength = (int)floor(sqrt($length));
        $dashString = '';

        while (strlen($str) > $dashLength) {
            $dashString .= substr($str, 0, $dashLength) . '-';
            $str = substr($str, $dashLength);
        }

        return $dashString . $str;
    }

    /**
     * Returns the short version of the given class name.
     *
     * @param string $className
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.1.0
     */
    public static function shortClassName(string $className): string
    {
        $str = strrchr($className, '\\');

        if ($str === false) {
            return $className;
        }

        return substr($str, 1);
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
    #[Pure]
    public static function splitSentences(string $str): array
    {
        return preg_split('/(?<!\.\.\.)(?<!Dr\.)(?<=[.?!]|\.\)|\.")\s+(?=[a-zA-Z"(])/', $str, flags: PREG_SPLIT_NO_EMPTY);
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
        preg_match_all('/([a-zA-Z\d]+)/', $str, $matches);

        return implode(array_map(ucfirst(...), $matches[0]));
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
        $str = strtr($str, ['-' => '_']);

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
    public static function truncateText(string $text, int $wordCount = 20, string $ending = '...'): string
    {
        $excerpt = $text;
        $excerpt = preg_replace("/<h2>.+?<\/h2>/is", "", $excerpt);
        $excerpt = preg_replace("/<h3>.+?<\/h3>/is", "", $excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = mb_trim($excerpt);
        $words = explode(' ', $excerpt, $wordCount + 1);

        if (count($words) > $wordCount) {
            array_pop($words);
            $excerpt = implode(' ', $words);
            $excerpt = mb_trim($excerpt, "!,.-");
            $excerpt .= $ending;
        }

        return trim($excerpt);
    }

}
