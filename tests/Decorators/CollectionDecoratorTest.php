<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use Mockery as m;

class CollectionDecoratorTest extends AbstractTestCase
{
    private $collectionDecorator;

    /**
     * @before
     */
    public function setUpProperties()
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
