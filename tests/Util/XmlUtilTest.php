<?php
declare(strict_types=1);

use Raxos\Foundation\Util\XmlUtil;

covers(XmlUtil::class);

it('escapes text and preserves booleans, nested values, lists and CDATA', function (): void {
    $serializable = new class implements JsonSerializable {

        public function jsonSerialize(): array
        {
            return ['value' => 'unit'];
        }

    };
    $xml = null;
    XmlUtil::arrayToXml(['text' => 'A & B', 'yes' => true, 'no' => false, 'html' => '<p>unit</p>', 'items' => ['a', 'b'], 'entry_list' => [1], 'values_without_suffix' => [2], 'object' => $serializable], $xml);
    expect((string)$xml->text)->toBe('A & B')->and((string)$xml->yes)->toBe('1')->and((string)$xml->no)->toBe('0')
        ->and((string)$xml->html)->toBe('<p>unit</p>')->and($xml->asXML())->toContain('<![CDATA[<p>unit</p>]]>')
        ->and((string)$xml->items->item[1])->toBe('b')->and((string)$xml->entry_list->entry)->toBe('1')
        ->and((string)$xml->values_without_suffix->item)->toBe('2')->and((string)$xml->object->value)->toBe('unit');
});

it('appends to a supplied XML root and names numeric root entries item', function (): void {
    $xml = new SimpleXMLElement('<custom><existing>keep</existing></custom>');
    XmlUtil::arrayToXml(['new', 'next' => null], $xml);
    expect($xml->getName())->toBe('custom')->and((string)$xml->existing)->toBe('keep')->and((string)$xml->item)->toBe('new')
        ->and((string)$xml->next)->toBe('');
});
