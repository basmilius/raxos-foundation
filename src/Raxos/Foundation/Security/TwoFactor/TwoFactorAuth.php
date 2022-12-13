<?php
declare(strict_types=1);

namespace Raxos\Foundation\Security\TwoFactor;

use JetBrains\PhpStorm\ExpectedValues;
use function array_search;
use function bindec;
use function ceil;
use function chr;
use function chunk_split;
use function decbin;
use function explode;
use function floor;
use function hash_equals;
use function hash_hmac;
use function implode;
use function in_array;
use function ord;
use function pack;
use function pow;
use function preg_match;
use function preg_quote;
use function random_bytes;
use function rawurlencode;
use function sprintf;
use function str_pad;
use function str_split;
use function strlen;
use function strtoupper;
use function substr;
use function time;
use function trim;
use function unpack;
use function vsprintf;
use const STR_PAD_LEFT;

/**
 * Class TwoFactorAuth
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Security\TwoFactor
 * @since 1.0.0
 */
class TwoFactorAuth
{

    private const BASE32 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '='];
    private const SUPPORTED_ALGORITHMS = ['sha1', 'sha256', 'sha512', 'md5'];

    /**
     * TwoFactorAuth constructor.
     *
     * @param string|null $issuer
     * @param int $digits
     * @param int $period
     * @param string $algorithm
     *
     * @throws TwoFactorAuthException
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(
        protected ?string $issuer = null,
        protected int $digits = 6,
        protected int $period = 30,
        #[ExpectedValues(values: self::SUPPORTED_ALGORITHMS)]
        protected string $algorithm = 'sha1'
    )
    {
        if ($this->digits <= 0) {
            throw new TwoFactorAuthException('The amount of digits must be a positive integer.', TwoFactorAuthException::ERR_INVALID_ARGUMENT);
        }

        if ($this->period <= 0) {
            throw new TwoFactorAuthException('The period must be a positive integer.', TwoFactorAuthException::ERR_INVALID_ARGUMENT);
        }

        if (!in_array($this->algorithm, self::SUPPORTED_ALGORITHMS)) {
            throw new TwoFactorAuthException(sprintf('The algorithm must be one of %s.', implode(', ', self::SUPPORTED_ALGORITHMS)), TwoFactorAuthException::ERR_INVALID_ARGUMENT);
        }
    }

    /**
     * Generates a new secret with the given amount of bits.
     *
     * @param int $bits
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     *
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function createSecret(int $bits = 160): string
    {
        $bytes = (int)ceil($bits / 5);
        $random = random_bytes($bytes);
        $secret = '';

        for ($i = 0; $i < $bytes; $i++) {
            $secret .= self::BASE32[ord($random[$i]) & 31];
        }

        return $secret;
    }

    /**
     * Generates a code for the given secret.
     *
     * @param string $secret
     * @param int|null $time
     *
     * @return string
     * @throws TwoFactorAuthException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function generateCode(string $secret, ?int $time = null): string
    {
        $time ??= $this->getTime();

        $secretKey = $this->base32Decode($secret);
        $timestamp = "\0\0\0\0" . pack('N*', $this->getTimeSlice($time));
        $hashHmac = hash_hmac($this->algorithm, $timestamp, $secretKey, true);
        $hashPart = substr($hashHmac, ord(substr($hashHmac, -1)) & 0x0F, 4);
        $value = unpack('N', $hashPart);
        $value = $value[1] & 0x7FFFFFFF;

        return str_pad((string)($value % pow(10, $this->digits)), $this->digits, '0', STR_PAD_LEFT);
    }

    /**
     * Generates the otpauth:// url for authenticator apps.
     *
     * @param string $secret
     * @param string $label
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function generateQrData(string $secret, string $label): string
    {
        return vsprintf('otpauth://totp/%s?secret=%s&issuer=%s&period=%d&algorithm=%s&digits=%d', [
            rawurlencode($label),
            rawurlencode($secret),
            rawurlencode($this->issuer),
            $this->period,
            rawurlencode(strtoupper($this->algorithm)),
            $this->digits
        ]);
    }

    /**
     * Verifies the given code.
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy
     *
     * @return bool
     * @throws TwoFactorAuthException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $timestamp = $this->getTime();
        $timeSlice = 0;

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $ts = $timestamp + $i * $this->period;
            $slice = $this->getTimeSlice($ts);
            $timeSlice = hash_equals($this->generateCode($secret, $ts), $code) ? $slice : $timeSlice;
        }

        return $timeSlice > 0;
    }

    /**
     * Decodes the provided base32 encoded string.
     *
     * @param string $value
     *
     * @return string
     * @throws TwoFactorAuthException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function base32Decode(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        if (preg_match('/[^' . preg_quote(implode('', self::BASE32)) . ']/', $value) !== 0) {
            throw new TwoFactorAuthException('The given value is an invalid base32 string.', TwoFactorAuthException::ERR_INVALID_BASE32);
        }

        $buffer = '';

        foreach (str_split($value) as $character) {
            if ($character !== '=') {
                $buffer .= str_pad(decbin(array_search($character, self::BASE32)), 5, '0', STR_PAD_LEFT);
            }
        }

        $length = strlen($buffer);
        $blocks = trim(chunk_split(substr($buffer, 0, $length - ($length % 8)), 8, ' '));
        $output = '';

        foreach (explode(' ', $blocks) as $block) {
            $output .= chr(bindec(str_pad($block, 8, '0')));
        }

        return $output;
    }

    /**
     * Gets the current unix timestamp.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function getTime(): int
    {
        return time();
    }

    /**
     * Gets a time slice with optional offset.
     *
     * @param int $time
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function getTimeSlice(int $time = 0): int
    {
        return (int)floor($time / $this->period);
    }

}
