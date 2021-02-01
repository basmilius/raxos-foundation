<?php
declare(strict_types=1);

namespace Raxos\Foundation\Util;

use JsonSerializable;
use SimpleXMLElement;
use function dom_import_simplexml;
use function htmlspecialchars;
use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function str_ends_with;
use function strip_tags;
use function substr;

/**
 * Class XmlUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Util
 * @since 1.0.0
 */
final class XmlUtil
{

    /**
     * Converts the given array into XML.
     *
     * @param array $arr
     * @param SimpleXMLElement|null $xml
     * @param string|null $parentName
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function arrayToXml(array $arr, SimpleXMLElement &$xml = null, ?string $parentName = null): void
    {
        $xml ??= new SimpleXMLElement('<root></root>');

        foreach ($arr as $key => $value) {
            if (is_int($key)) {
                $key = $parentName !== null ? self::generateSingularName($parentName) : 'item';
            }

            if ($value instanceof JsonSerializable) {
                $value = $value->jsonSerialize();
            }

            if (is_array($value)) {
                $item = $xml->addChild($key);
                self::arrayToXml($value, $item, $key);
            } else if (is_bool($value)) {
                $xml->addChild($key, $value ? '1' : '0');
            } else if (is_string($value) && $value !== strip_tags($value)) {
                $xml->{$key} = null;
                $node = dom_import_simplexml($xml->{$key});
                $doc = $node->ownerDocument;
                $node->appendChild($doc->createCDATASection($value));
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value));
            }
        }
    }

    /**
     * Generates a singular from the given plural.
     *
     * @param string $name
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private static function generateSingularName(string $name): string
    {
        if (str_ends_with($name, 's')) {
            return substr($name, 0, -1);
        } else if (str_ends_with($name, '_list')) {
            return substr($name, 0, -5);
        } else {
            return 'item';
        }
    }

}
