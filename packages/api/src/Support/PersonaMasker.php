<?php

namespace Persona\Api\Support;

/**
 * Deterministic PII masking used by the API JsonResources.
 *
 * Masks are intentionally coarse and one-way: they reveal only a structural
 * hint (first letter / trailing digits) so lists and summary views stay
 * useful without leaking raw decrypted values. The raw value flows to the
 * client exclusively via `PersonaPolicy@viewSensitive`.
 */
class PersonaMasker
{
    /**
     * Mask a contact value by its type.
     *
     * Email: `ali@example.com`   => `a***@example.com`
     * Phone: `+1 555 0100 5678`  => `****5678`
     * Other (handle/username):   => `****5678` (trailing 4 kept)
     */
    public static function contact(string $type, string $value): string
    {
        if ($type === 'email' && str_contains($value, '@')) {
            [$local, $domain] = explode('@', $value, 2) + [1 => ''];

            return mb_substr($local, 0, 1) . '***' . ($domain !== '' ? '@' . $domain : '');
        }

        return self::trailingMask($value);
    }

    /**
     * Mask a document number: `AB12345678` => `****5678`.
     */
    public static function documentNumber(string $number): string
    {
        return self::trailingMask($number);
    }

    /**
     * Mask a tax identifier: `DE123456789` => `****6789`.
     */
    public static function taxId(string $taxId): string
    {
        return self::trailingMask($taxId);
    }

    private static function trailingMask(string $value): string
    {
        return $value === '' ? '****' : '****' . mb_substr($value, -4);
    }
}