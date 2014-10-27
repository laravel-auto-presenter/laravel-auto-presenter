<?php

namespace McCool\Tests\Decorators;

use GrahamCampbell\TestBench\AbstractTestCase;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
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

        $this->assertEquals('McCool\LaravelAutoPresenter\Decorators\AtomDecorator', $class);
    }

    /**
     * @expectedException \McCool\LaravelAutoPresenter\Exceptions\DecoratorNotFound
     */
    public function testCreationOfANonExistentDecorator()
    {
        $this->baseDecorator->createDecorator('bulbous');
    }
}
