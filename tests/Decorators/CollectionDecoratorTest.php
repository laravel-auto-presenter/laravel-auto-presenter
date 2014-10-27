<?php

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use Mockery as m;

class CollectionDecoratorTest extends AbstractTestCase
{
    private $collectionDecorator;

    protected function start()
    {
        $container = m::mock('Illuminate\Contracts\Container\Container');
        $this->collectionDecorator = new CollectionDecorator($container);
    }

    public function testCanDecorateCollection()
    {
        $collection = m::mock('Illuminate\Support\Collection');

        $this->assertTrue($this->collectionDecorator->canDecorate($collection));
        $this->assertFalse($this->collectionDecorator->canDecorate('garbage stuff yo'));
    }

    public function testDecorationOfACollection()
    {
        $collection = m::mock('Illuminate\Support\Collection')->makePartial();

        $collection->shouldReceive('put')->with(2, 'something');

        $this->collectionDecorator->decorate($collection);
    }
}
