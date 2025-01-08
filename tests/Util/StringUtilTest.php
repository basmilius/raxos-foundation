<?php
declare(strict_types=1);

namespace Util;

use Raxos\Foundation\Util\StringUtil;
use function describe;
use function it;

describe('commaCommaAnd', function (): void {
    it('should return the correct string for one value.', function (): void {
        $arr = ['First'];
        $result = StringUtil::commaCommaAnd($arr);

        expect($result)->toBe('First');
    });

    it('should return the correct string for multiple values.', function (): void {
        $arr = ['First', 'Second', 'Third'];
        $result = StringUtil::commaCommaAnd($arr);

        expect($result)->toBe('First, Second & Third');
    });
});

describe('formatBytes', function (): void {
    it('should return the correct result for one kilobyte.', function (): void {
        $result = StringUtil::formatBytes(1024);

        expect($result)->toBe('1 kB');
    });

    it('should return the correct result for one megabyte.', function (): void {
        $result = StringUtil::formatBytes(1024 * 1024);

        expect($result)->toBe('1 MB');
    });
});

describe('isSerialized', function (): void {
    it('should return true for a serialized string.', function (): void {
        $result = StringUtil::isSerialized('a:1:{s:3:"foo";s:3:"bar";}');

        expect($result)->toBeTrue();
    });

    it('should return false for a non-serialized string.', function (): void {
        $result = StringUtil::isSerialized('foo');

        expect($result)->toBeFalse();
    });
});

describe('multiByteSubstringReplace', function (): void {
    it('should correctly replace a multibyte substring in the middle of the string.', function (): void {
        $string = 'こんにちは世界';
        $replacement = 'さようなら';
        $start = 2;
        $length = 3;

        $result = StringUtil::multiByteSubstringReplace($string, $replacement, $start, $length);

        expect($result)->toBe('こんさようなら世界');
    });

    it('should correctly replace a multibyte substring at the start of the string.', function (): void {
        $string = 'こんにちは世界';
        $replacement = 'さようなら';
        $start = 0;
        $length = 3;

        $result = StringUtil::multiByteSubstringReplace($string, $replacement, $start, $length);

        expect($result)->toBe('さようならちは世界');
    });
});

describe('random', function (): void {
    it('should return a string of the specified length.', function (): void {
        $length = 10;
        $result = StringUtil::random($length);

        expect($result)->toHaveLength($length);
    });

    it('should return a non-empty string when length is greater than zero.', function (): void {
        $length = 5;
        $result = StringUtil::random($length);

        expect($result)->not->toBe('');
    });

    it('should return only alphanumeric characters when sets does not include special characters.', function (): void {
        $length = 16;
        $result = StringUtil::random($length, sets: 'lud');

        expect($result)->toMatch('/^[a-zA-Z0-9]+$/');
    });
});

describe('shortClassName', function (): void {
    it('should return the short class name for a fully qualified class name.', function (): void {
        // Define a temporary class under a namespace
        $namespace = 'TempNamespace';
        $className = 'TempClass';
        eval("namespace $namespace; class $className {}");

        $fullyQualifiedName = "$namespace\\$className";
        $result = StringUtil::shortClassName($fullyQualifiedName);

        expect($result)->toBe($className);
    });

    it('should return the same name when no namespace is present.', function (): void {
        // Define a temporary class without a namespace
        $className = 'TempClassNoNamespace';
        eval("class $className {}");

        $result = StringUtil::shortClassName($className);

        expect($result)->toBe($className);
    });
});

describe('slugify', function (): void {
    $dataset = [
        ['Hello World!', 'hello-world'],
        ['PHP is Awesome', 'php-is-awesome'],
        ['  Spaces   Everywhere  ', 'spaces-everywhere'],
        ['Special@#Characters!!', 'special-characters'],
        ['UTF8: こんにちは 世界', 'utf8-konnichiha-shijie']
    ];

    it('should correctly slugify strings.', function (string $input, string $expected): void {
        $result = StringUtil::slugify($input);

        expect($result)->toBe($expected);
    })->with($dataset);
});

describe('splitSentences', function (): void {
    $dataset = [
        ['The quick brown fox. Jumps over the lazy dog.', ['The quick brown fox.', 'Jumps over the lazy dog.']],
        ["Hello world! What's your name?", ['Hello world!', "What's your name?"]],
        ['This... is an example.', ['This... is an example.']],
        ['', []],
        ['No punctuation', ['No punctuation']],
        ['Multiple sentences! Even with weird punctuation?', ['Multiple sentences!', 'Even with weird punctuation?']],
    ];

    it('should correctly split text into sentences.', function (string $input, array $expected): void {
        $result = StringUtil::splitSentences($input);

        expect($result)->toBe($expected);
    })->with($dataset);
});

describe('toPascalCase', function (): void {
    it('should convert a snake_case string to PascalCase.', function (): void {
        $input = 'hello_world';
        $result = StringUtil::toPascalCase($input);

        expect($result)->toBe('HelloWorld');
    });

    it('should convert a string with spaces and dashes to PascalCase.', function (): void {
        $input = 'hello-world how_are_you';
        $result = StringUtil::toPascalCase($input);

        expect($result)->toBe('HelloWorldHowAreYou');
    });
});

describe('toSnakeCase', function (): void {
    it('should convert a PascalCase string to snake_case.', function (): void {
        $input = 'HelloWorld';
        $result = StringUtil::toSnakeCase($input);

        expect($result)->toBe('hello_world');
    });

    it('should convert a kebab-case string to snake_case.', function (): void {
        $input = 'hello-world-example';
        $result = StringUtil::toSnakeCase($input);

        expect($result)->toBe('hello_world_example');
    });
});

describe('truncateText', function (): void {
    it('should truncate text to the specified number of words.', function (): void {
        $text = 'The quick brown fox jumps over the lazy dog.';
        $wordCount = 4;
        $ending = '...';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('The quick brown fox...');
    });

    it('should append the specified ending to truncated text.', function (): void {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $wordCount = 5;
        $ending = ' (read more)';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('Lorem ipsum dolor sit amet (read more)');
    });

    it('should return the full text if word count is greater than the number of words in the text.', function (): void {
        $text = 'Short text example.';
        $wordCount = 10;
        $ending = '...';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe($text);
    });

    it('should correctly remove h2 elements when truncating a small blog post.', function (): void {
        $text = '<h2>My Blog Post</h2> This is a small blog post containing an example of truncation in PHP.';
        $wordCount = 7;
        $ending = '...';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('This is a small blog post containing...');
    });

    it('should handle empty text correctly.', function (): void {
        $text = '';
        $wordCount = 5;
        $ending = '...';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('');
    });

    it('should handle cases where the text contains HTML but no h2 elements.', function (): void {
        $text = '<p>This is a test paragraph with some <strong>bold</strong> text.</p>';
        $wordCount = 6;
        $ending = '...';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('This is a test paragraph with...');
    });

    it('should handle cases where the ending is an empty string.', function (): void {
        $text = 'The quick brown fox jumps over the lazy dog.';
        $wordCount = 4;
        $ending = '';

        $result = StringUtil::truncateText($text, $wordCount, $ending);

        expect($result)->toBe('The quick brown fox');
    });
});
