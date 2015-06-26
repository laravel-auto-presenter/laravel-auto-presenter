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
use Illuminate\Contracts\Container\Container;
use McCool\LaravelAutoPresenter\AutoPresenter;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\HasPresenter;
use McCool\Tests\Stubs\ModelPresenter;
use McCool\Tests\Stubs\ModelStub;
use Mockery;

class AtomDecoratorTest extends AbstractTestCase
{
    private $decorator;

    /**
     * @before
     */
    public function setUpProperties()
    {
        $this->decorator = new AtomDecorator(Mockery::mock(AutoPresenter::class), Mockery::mock(Container::class));
    }

    public function testCanDecoratePresenter()
    {
        $this->assertTrue($this->decorator->canDecorate(Mockery::mock(HasPresenter::class)));
    }

    public function testCannotDecorateGarbage()
    {
        $this->assertFalse($this->decorator->canDecorate([]));
        $this->assertFalse($this->decorator->canDecorate(null));
        $this->assertFalse($this->decorator->canDecorate('garbage stuff yo'));
    }

    public function testShouldHandleRelations()
    {
        $model = Mockery::mock(ModelStub::class);
        $relations = ['blah'];

        $this->decorator->getAutoPresenter()->shouldReceive('decorate')->once()
            ->with($relations[0])->andReturn('foo');

        $model->shouldReceive('getRelations')->once()->andReturn($relations);
        $model->shouldReceive('setRelation')->once()->with(0, 'foo');
        $model->shouldReceive('getPresenterClass')->once()->andReturn(ModelPresenter::class);
        $this->decorator->getContainer()->shouldReceive('make')->once()->andReturn(new ModelPresenter($model));

        $this->decorator->decorate($model);
    }
}
