<?php namespace McCool\Tests;

use McCool\LaravelAutoPresenter\Exceptions\MethodNotFound;
use McCool\LaravelAutoPresenter\Exceptions\PropertyNotFound;
use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;

class BasePresenterTest extends AbstractTestCase
{
    private $decoratedAtom;

    protected function start()
    {
        $this->decoratedAtom = $this->app->make(DecoratedAtom::class);
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
        try {
            $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
            $presenter->thisPropertyDoesntExist;
        } catch (PropertyNotFound $e) {
            $property = 'thisPropertyDoesntExist';
            $class = DecoratedAtomPresenter::class;
            $this->assertEquals("The property '$property' was not found on the presenter class '$class'.", $e->getMessage());
            $this->assertEquals($property, $e->getProperty());
            $this->assertEquals($class, $e->getClass());
            throw $e;
        }
    }

    /**
     * @expectedException \McCool\LaravelAutoPresenter\Exceptions\MethodNotFound
     */
    public function testResourceMethodNotFoundThrowsException()
    {
        try {
            $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
            $presenter->thisMethodDoesntExist();
        } catch (MethodNotFound $e) {
            $method = 'thisMethodDoesntExist';
            $class = DecoratedAtomPresenter::class;
            $this->assertEquals("The method '$method' was not found on the presenter class '$class'.", $e->getMessage());
            $this->assertEquals($method, $e->getMethod());
            $this->assertEquals($class, $e->getClass());
            throw $e;
        }
    }
}
