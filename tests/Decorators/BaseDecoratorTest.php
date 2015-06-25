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
use McCool\LaravelAutoPresenter\Exceptions\DecoratorNotFound;
use McCool\Tests\Stubs\BaseDecoratorStub;
use Mockery as m;

class BaseDecoratorTest extends AbstractTestCase
{
    private $baseDecorator;

    protected function start()
    {
        $container = m::mock('Illuminate\Contracts\Container\Container');
        $this->baseDecorator = new BaseDecoratorStub($container);
    }

    public function testObjectCreationShouldReturnAppropriateDecorator()
    {
        $this->baseDecorator->getContainer()->shouldReceive('make')->once()
            ->with(AtomDecorator::class)->andReturn(new AtomDecorator($this->baseDecorator->getContainer()));

        $class = get_class($this->baseDecorator->createDecorator('Atom'));

        $this->assertSame('McCool\LaravelAutoPresenter\Decorators\AtomDecorator', $class);
    }

    /**
     * @expectedException \McCool\LaravelAutoPresenter\Exceptions\DecoratorNotFound
     */
    public function testCreationOfANonExistentDecorator()
    {
        try {
            $this->baseDecorator->createDecorator('Bulbous');
        } catch (DecoratorNotFound $e) {
            $class = 'McCool\LaravelAutoPresenter\Decorators\BulbousDecorator';
            $this->assertSame("The decorator class '$class' was not found.", $e->getMessage());
            $this->assertSame($class, $e->getClass());
            throw $e;
        }
    }
}
