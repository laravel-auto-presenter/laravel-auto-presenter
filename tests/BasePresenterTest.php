<?php namespace McCool\Tests;

use Illuminate\Container\Container;
use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;

class BasePresenterTest extends TestCase
{
    private $decoratedAtom;

    public function setUp()
    {
        $container = new Container();
        $this->decoratedAtom = new DecoratedAtom($container);
    }

    public function testResourceIsReturned()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals($this->decoratedAtom, $presenter->getWrappedObject());
    }

    public function testResourceAttributeDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals(DecoratedAtomPresenter::class, $presenter->getPresenterClass());
    }

    public function testPresenterMethodDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals('Primer', $presenter->favoriteMovie);
    }

    public function testResourcePropertyViaMagicMethod()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals('bazinga', $presenter->myProperty);
    }

    public function testMagicMethodProperty()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertEquals('woop', $presenter->testProperty);
    }

    /**
     * @expectedException \McCool\LaravelAutoPresenter\Exceptions\PropertyNotFound
     */
    public function testResourcePropertyNotFoundThrowsException()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $presenter->thisPropertyDoesntExist;
    }

    /**
     * @expectedException \McCool\LaravelAutoPresenter\Exceptions\MethodNotFound
     */
    public function testResourceMethodNotFoundThrowsException()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $presenter->thisMethodDoesntExist();
    }
}
