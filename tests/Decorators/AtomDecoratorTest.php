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
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\HasPresenter;
use Mockery as m;

class AtomDecoratorTest extends AbstractTestCase
{
    private $atomDecorator;

    /**
     * @before
     */
    public function setUpProperties()
    {
        $container = m::mock('Illuminate\Contracts\Container\Container');
        $this->atomDecorator = new AtomDecorator($container);
    }

    public function testCanDecorateModel()
    {
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $this->assertTrue($this->atomDecorator->canDecorate($model));
    }

    public function testCanDecoratePresenterInterface()
    {
        $subject = m::mock(HasPresenter::class);
        $this->assertTrue($this->atomDecorator->canDecorate($subject));
    }

    public function testCannotDecorateGarbage()
    {
        $this->assertFalse($this->atomDecorator->canDecorate([]));
        $this->assertFalse($this->atomDecorator->canDecorate(null));
    }

    public function testShouldHandleRelationsWhenSubjectIsAModel()
    {
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $relations = ['blah'];

        $model->shouldReceive('getRelations')->andReturn($relations);
        $model->shouldReceive('setRelation')->with(0, $relations[0]);

        $this->atomDecorator->decorate($model);
    }

    public function testShouldHandleRelationsWhenSubjectIsAModelWithACollection()
    {
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        $relations = [m::mock('Illuminate\Support\Collection')->makePartial()];

        $model->shouldReceive('getRelations')->andReturn($relations);
        $model->shouldReceive('setRelation')->with(0, $relations[0]);

        $this->atomDecorator->getContainer()->shouldReceive('make')->once()
            ->with('McCool\LaravelAutoPresenter\Decorators\CollectionDecorator')
            ->andReturn(new CollectionDecorator($this->atomDecorator->getContainer()));

        $this->atomDecorator->decorate($model);
    }
}
