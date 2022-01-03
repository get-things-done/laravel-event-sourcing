<?php

namespace Spatie\EventSourcing\Tests;

use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;
use Spatie\EventSourcing\Tests\TestClasses\AggregateRoots\AccountAggregateRoot;
use Spatie\EventSourcing\Tests\TestClasses\AggregateRoots\StorableEvents\MoneyAdded;

class EloquentStoredEventRepositoryTest extends TestCase
{
    /** @test */
    public function it_can_get_the_latest_version_id_for_a_given_aggregate_uuid()
    {
        $eloquentStoredEventRepository = new EloquentStoredEventRepository();

        $this->assertEquals(0, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186750'));

        $aggregateRoot = AccountAggregateRoot::retrieve('1537200202186751');
        $this->assertEquals(0, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186751'));

        $aggregateRoot->addMoney(100)->persist();
        $this->assertEquals(1, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186751'));

        $aggregateRoot->addMoney(100)->persist();
        $this->assertEquals(2, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186751'));

        $anotherAggregateRoot = AccountAggregateRoot::retrieve('1537200202186752');
        $anotherAggregateRoot->addMoney(100)->persist();
        $this->assertEquals(1, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186752'));
        $this->assertEquals(2, $eloquentStoredEventRepository->getLatestAggregateVersion('1537200202186751'));
    }

    /** @test */
    public function it_sets_the_original_event_on_persist()
    {
        $eloquentStoredEventRepository = app(EloquentStoredEventRepository::class);

        $originalEvent = new MoneyAdded(100);
        $storedEvent = $eloquentStoredEventRepository->persist($originalEvent, '1537200202186751', 1);

        $this->assertSame($originalEvent, $storedEvent->event);
    }
}
