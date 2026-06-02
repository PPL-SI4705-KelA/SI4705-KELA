<?php

namespace App\Helpers;

class CsvSafeFormatter
{
    /**
     * Sanitizes a cell value to prevent CSV Injection (Formula Injection).
     * Prepend a single quote if the value starts with: =, +, -, @, \t, \r
     */
    public static function escapeCell($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        if ($value === '') {
            return '';
        }

        $triggerChars = ['=', '+', '-', '@', "\t", "\r"];
        $firstChar = substr($value, 0, 1);

        if (in_array($firstChar, $triggerChars, true)) {
            return "'" . $value;
        }

        return $value;
    }
}
