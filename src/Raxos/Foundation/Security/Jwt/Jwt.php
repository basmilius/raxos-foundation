<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\Jwt;

use JsonException;
use Raxos\Foundation\Util\Base64;
use function array_key_exists;
use function array_shift;
use function count;
use function date;
use function explode;
use function hash_equals;
use function hash_hmac;
use function implode;
use function json_decode;
use function json_encode;
use function json_last_error;
use function openssl_error_string;
use function openssl_sign;
use function openssl_verify;
use function sprintf;
use const JSON_BIGINT_AS_STRING;
use const JSON_ERROR_CTRL_CHAR;
use const JSON_ERROR_DEPTH;
use const JSON_ERROR_NONE;
use const JSON_ERROR_STATE_MISMATCH;
use const JSON_ERROR_SYNTAX;
use const JSON_ERROR_UTF8;
use const JSON_THROW_ON_ERROR;

/**
 * Class Jwt
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security\Jwt
 * @since 1.0.0
 */
class Jwt
{

    public static ?int $currentTime = null;

    public static int $leeway = 0;

    public static array $supportedAlgorithms = [
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];

    /**
     * Decodes a JWT string into an array.
     *
     * @param string $jwt
     * @param string[] $keys
     * @param array $allowedAlgorithms
     *
     * @return array
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     *
     * @uses JWT::jsonDecode()
     * @uses JWT::urlsafeB64Decode()
     */
    public static function decode(string $jwt, array $keys, array $allowedAlgorithms = []): array
    {
        $currentTime = static::$currentTime ?? time();

        if (empty($keys)) {
            throw new JwtException('At least one key is required.', JwtException::ERR_INVALID_ARGUMENT);
        }

        $segments = explode('.', $jwt);

        if (count($segments) !== 3) {
            throw new JwtException('Wrong number of segments.', JwtException::ERR_UNEXPECTED_ARGUMENT);
        }

        [$header64, $payload64, $signature64] = $segments;

        $header = static::jsonDecode(Base64::decodeUrlSafe($header64));
        $payload = static::jsonDecode(Base64::decodeUrlSafe($payload64));
        $signature = Base64::decodeUrlSafe($signature64);

        if ($header === null || $payload === null || empty($signature)) {
            throw new JwtException('Invalid encoding of segment.', JwtException::ERR_UNEXPECTED_ARGUMENT);
        }

        if (!array_key_exists('alg', $header)) {
            throw new JwtException('Unknown algorithm.', JwtException::ERR_UNEXPECTED_ARGUMENT);
        }

        if (!isset(static::$supportedAlgorithms[$header['alg']])) {
            throw new JwtException('Algorithm not supported.', JwtException::ERR_UNSUPPORTED);
        }

        if (count($allowedAlgorithms) > 0 && !in_array($header['alg'], $allowedAlgorithms, true)) {
            throw new JwtException('Algorithm not allowed.', JwtException::ERR_UNEXPECTED_ARGUMENT);
        }

        if (count($keys) > 1) {
            if (isset($header['kid'])) {
                if (isset($keys[$header['kid']])) {
                    $key = $keys[$header['kid']];
                } else {
                    throw new JwtException('kid is invalid, key does not exist.', JwtException::ERR_UNEXPECTED_ARGUMENT);
                }
            } else {
                throw new JwtException('kid is missing in JWT payload.', JwtException::ERR_UNEXPECTED_ARGUMENT);
            }
        } else {
            $key = array_shift($keys);
        }

        if (!self::verify(sprintf('%s.%s', $header64, $payload64), $signature, $key, $header['alg'])) {
            throw new JwtException('Invalid signature.', JwtException::ERR_INVALID_SIGNATURE);
        }

        if (array_key_exists('nbf', $payload) && $payload['nbf'] > ($currentTime + static::$leeway)) {
            throw new JwtException(sprintf('Cannot handle token prior to %s', date('Y-m-d\TH:i:sO', $payload['nbf'])), JwtException::ERR_NOT_YET_VALID);
        }

        if (array_key_exists('iat', $payload) && $payload['iat'] > ($currentTime + static::$leeway)) {
            throw new JwtException(sprintf('Cannot handle token prior to %s', date('Y-m-d\TH:i:sO', $payload['iat'])), JwtException::ERR_NOT_YET_VALID);
        }

        if (array_key_exists('exp', $payload) && ($currentTime - static::$leeway) >= $payload['exp']) {
            throw new JwtException('Expired token', JwtException::ERR_EXPIRED);
        }

        return $payload;
    }

    /**
     * Converts and signs an array into a JWT string.
     *
     * @param array $payload
     * @param string $key
     * @param string $algorithmName
     * @param null $keyId
     * @param array $headers
     *
     * @return string
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     *
     * @uses JWT::jsonEncode()
     * @uses JWT::urlsafeB64Encode()
     */
    public static function encode(array $payload, string $key, string $algorithmName = 'HS256', $keyId = null, array $headers = []): string
    {
        $headers['typ'] = 'JWT';
        $headers['alg'] = $algorithmName;

        if ($keyId !== null) {
            $headers['kid'] = $keyId;
        }

        $segments = [];
        $segments[] = Base64::encodeUrlSafe(static::jsonEncode($headers));
        $segments[] = Base64::encodeUrlSafe(static::jsonEncode($payload));

        $plainToken = implode('.', $segments);

        $signature = static::sign($plainToken, $key, $algorithmName);
        $segments[] = Base64::encodeUrlSafe($signature);

        return implode('.', $segments);
    }

    /**
     * Signs a string with the given key and algorithm.
     *
     * @param string $message
     * @param string $key
     * @param string $algorithmName
     *
     * @return string
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    public static function sign(string $message, string $key, string $algorithmName): string
    {
        if (!isset(static::$supportedAlgorithms[$algorithmName])) {
            throw new JwtException('Algorithm not supported.', JwtException::ERR_UNSUPPORTED);
        }

        [$function, $algorithm] = static::$supportedAlgorithms[$algorithmName];

        switch ($function) {
            case 'hash_hmac':
                return hash_hmac($algorithm, $message, $key, true);

            case 'openssl':
                $signature = '';
                $success = openssl_sign($message, $signature, $key, $algorithm);

                if (!$success) {
                    throw new JwtException('Unable to sign data.', JwtException::ERR_OPENSSL);
                }

                return $signature;

            default:
                throw new JwtException('Algorithm not supported.', JwtException::ERR_UNSUPPORTED);
        }
    }

    /**
     * Verifies a signature with the message, key and algorithm. Not all methods
     * are symmetric, so we must have a separate verify and sign method.
     *
     * @param string $message
     * @param string $signature
     * @param string $key
     * @param string $algorithmName
     *
     * @return bool
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    private static function verify(string $message, string $signature, string $key, string $algorithmName): bool
    {
        if (!isset(static::$supportedAlgorithms[$algorithmName])) {
            throw new JwtException('Algorithm not supported.', JwtException::ERR_UNSUPPORTED);
        }

        [$function, $algorithm] = static::$supportedAlgorithms[$algorithmName];

        switch ($function) {
            case 'openssl':
                $result = openssl_verify($message, $signature, $key, $algorithm);

                if ($result === -1) {
                    throw new JwtException(openssl_error_string(), JwtException::ERR_OPENSSL);
                }

                return $result === 1;

            case'hash_hmac':
            default:
                $hash = hash_hmac($algorithm, $message, $key, true);

                return hash_equals($signature, $hash);
        }
    }

    /**
     * Decodes a JSON string into an array.
     *
     * @param string $input
     *
     * @return mixed
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    public static function jsonDecode(string $input): mixed
    {
        try {
            $data = json_decode($input, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);

            if (($errorCode = json_last_error()) !== JSON_ERROR_NONE) {
                self::onJSONError($errorCode);
            }

            if ($data === null && $input !== 'null') {
                throw new JwtException('NULL result with non-NULL input.', JwtException::ERR_NULL_RESULT);
            }

            return $data;
        } catch (JsonException $err) {
            throw new JwtException($err->getMessage(), $err->getCode(), $err);
        }
    }

    /**
     * Encodes an array into a JSON string.
     *
     * @param mixed $data
     *
     * @return string
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    public static function jsonEncode(mixed $data): string
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR);

            if (($errorCode = json_last_error()) !== JSON_ERROR_NONE) {
                self::onJSONError($errorCode);
            }

            if ($json === 'null' && $data !== null) {
                throw new JwtException('NULL result with non-NULL data.', JwtException::ERR_NULL_RESULT);
            }

            return $json;
        } catch (JsonException $err) {
            throw new JwtException($err->getMessage(), $err->getCode(), $err);
        }
    }

    /**
     * Invoked when a JSON error occurs.
     *
     * @param int $errorCode
     *
     * @throws JwtException
     * @author Bas Milius <bas@mili.us>
     * @since 1.5.0
     */
    private static function onJSONError(int $errorCode): void
    {
        $messages = [
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters'
        ];

        throw new JwtException($messages[$errorCode] ?? sprintf('Unknown JSON error: %d', $errorCode), JwtException::ERR_JSON_ERROR);
    }

}
