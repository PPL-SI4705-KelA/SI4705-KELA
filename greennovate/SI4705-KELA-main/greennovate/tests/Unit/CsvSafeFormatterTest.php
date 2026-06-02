<?php

namespace Tests\Unit;

use App\Helpers\CsvSafeFormatter;
use PHPUnit\Framework\TestCase;

class CsvSafeFormatterTest extends TestCase
{
    public function test_standard_values_are_not_escaped(): void
    {
        $this->assertEquals('Clean Text', CsvSafeFormatter::escapeCell('Clean Text'));
        $this->assertEquals('12345', CsvSafeFormatter::escapeCell('12345'));
        $this->assertEquals('12.34', CsvSafeFormatter::escapeCell(12.34));
    }

    public function test_null_value_returns_empty_string(): void
    {
        $this->assertEquals('', CsvSafeFormatter::escapeCell(null));
    }

    public function test_empty_string_returns_empty_string(): void
    {
        $this->assertEquals('', CsvSafeFormatter::escapeCell(''));
    }

    public function test_injection_characters_are_escaped_with_single_quote(): void
    {
        $this->assertEquals("'=1+1", CsvSafeFormatter::escapeCell('=1+1'));
        $this->assertEquals("'+123", CsvSafeFormatter::escapeCell('+123'));
        $this->assertEquals("'-456", CsvSafeFormatter::escapeCell('-456'));
        $this->assertEquals("'@username", CsvSafeFormatter::escapeCell('@username'));
        $this->assertEquals("'\tTab", CsvSafeFormatter::escapeCell("\tTab"));
        $this->assertEquals("'\rReturn", CsvSafeFormatter::escapeCell("\rReturn"));
    }
}
