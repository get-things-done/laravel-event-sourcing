<?php

namespace Spatie\EventSourcing\Tests\TestClasses;

use Godruoyi\Snowflake\Snowflake;

class FakeUuid
{
    protected static int $count = 1;

    public static function generate()
    {

        return '153720020218'.sprintf('%04d', self::$count++);
    }

    public static function reset()
    {
        self::$count = 1;
    }
}
