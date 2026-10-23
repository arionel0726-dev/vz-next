<?php

namespace App\Support;

class Translation
{
    /**
     * Pick a value out of a locale-keyed JSON column
     * (e.g. {"ro": "...", "ru": "..."}), preferring the current
     * app locale, then "ro"/"ru"/"en", then whatever is first.
     *
     * @param  array<string, string>|null  $values
     */
    public static function pick(?array $values, ?string $locale = null): ?string
    {
        if (empty($values)) {
            return null;
        }

        $locale = $locale ?? app()->getLocale();

        foreach ([$locale, 'ro', 'ru', 'en'] as $candidate) {
            if (! empty($values[$candidate])) {
                return (string) $values[$candidate];
            }
        }

        $first = reset($values);

        return $first !== false ? (string) $first : null;
    }
}
