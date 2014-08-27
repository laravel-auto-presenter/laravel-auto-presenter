<?php

namespace McCool\Tests\Decorators;

use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\Tests\TestCase;
use Mockery as m;

class CollectionDecoratorTest extends TestCase
{
    private $collectionDecorator;

    public function setUp()
    {
        $this->collectionDecorator = new CollectionDecorator();
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
