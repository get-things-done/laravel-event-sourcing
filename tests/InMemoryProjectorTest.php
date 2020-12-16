<?php

namespace Spatie\EventSourcing\Tests;

use Carbon\Carbon;
use Spatie\EventSourcing\Tests\TestClasses\AggregateRoots\StorableEvents\MoneyAdded;
use Spatie\EventSourcing\Tests\TestClasses\Projectors\ReportProjector;

class InMemoryProjectorTest extends TestCase
{
    /** @test */
    public function test_apply_from_the_past()
    {
        Carbon::setTestNow('2019-01-01');

        event(new MoneyAdded(10));

        Carbon::setTestNow('2020-01-01');

        event(new MoneyAdded(10));
        event(new MoneyAdded(20));

        $report = new ReportProjector('2020-01-01');

        $this->assertEquals(30, $report->money);
    }

    /** @test */
    public function test_apply_from_the_past_as_wel_as_new_ones()
    {
        $this->markTestSkipped("Not sure if we need to implement this");

        Carbon::setTestNow('2020-01-01');

        event(new MoneyAdded(10));

        $report = new ReportProjector('2020-01-01');

        event(new MoneyAdded(20));

        $this->assertEquals(30, $report->money);
    }
}