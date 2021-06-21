<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ReadingTimeService;

class ReadingTimeTest extends TestCase
{
    public function test_article_reading_time()
    {
        $time = ReadingTimeService::calculate(981);
        $this->assertEquals('4 minutes', $time);
        $time = ReadingTimeService::calculate(1700);
        $this->assertEquals('8 minutes', $time);
    }
}
