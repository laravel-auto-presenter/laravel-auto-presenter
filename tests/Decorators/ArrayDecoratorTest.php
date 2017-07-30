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
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\Decorators\ArrayDecorator;
use Mockery;

class ArrayDecoratorTest extends AbstractTestCase
{
    private $decorator;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->decorator = new ArrayDecorator(Mockery::mock(AutoPresenter::class));
    }

    public function testCanDecorateArray()
    {
        $this->assertTrue($this->decorator->canDecorate([]));
    }

    public function testCanDecorateCollection()
    {
        if (version_compare(PHP_VERSION, '7.0.2') > -1 && version_compare(PHP_VERSION, '7.1') < -1) {
            $this->markTestSkipped('Skipped due to mockery incompatibility.');
        }

        $this->assertTrue($this->decorator->canDecorate(Mockery::mock(Collection::class)));
    }

    public function testCannotDecorateGarbage()
    {
        $this->assertFalse($this->decorator->canDecorate(null));
        $this->assertFalse($this->decorator->canDecorate('garbage stuff yo'));
    }

    public function testDecorationOfCollection()
    {
        if (version_compare(PHP_VERSION, '7.0.2') > -1 && version_compare(PHP_VERSION, '7.1') < -1) {
            $this->markTestSkipped('Skipped due to mockery incompatibility.');
        }

        $collection = Mockery::mock(Collection::class)->makePartial();

        $collection->shouldReceive('put')->with(2, 'something');

        $this->decorator->decorate($collection);
    }
}
