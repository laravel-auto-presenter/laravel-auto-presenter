<?php namespace McCool\Tests;

use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomFieldsPresenter;
use McCool\Tests\Stubs\DecoratedAtomPresenter;
use Mockery as m;

class BasePresenterTest extends TestCase
{
    private $decoratedAtom;

    public function setUp()
    {
        $this->decoratedAtom = new DecoratedAtom();
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
     * @covers presenter::thisMethodDoesntExist
     * @expectedException \McCool\LaravelAutoPresenter\MethodNotFound
     */
    public function testResourcePropertyNotFoundThrowsException()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $presenter->thisPropertyDoesntExist;
    }

    /**
     * @covers presenter::thisMethodDoesntExist
     * @expectedException \McCool\LaravelAutoPresenter\MethodNotFound
     */
    public function testResourceMethodNotFoundThrowsException()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $presenter->thisMethodDoesntExist();
    }
}
