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
        $this->assertSame($this->decoratedAtom, $presenter->getWrappedObject());
    }

    public function testResourceAttributeDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame(DecoratedAtomPresenter::class, $presenter->getPresenterClass());
    }

    public function testPresenterMethodDeference()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame('Primer', $presenter->favoriteMovie);
    }

    public function testResourcePropertyViaMagicMethod()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame('bazinga', $presenter->myProperty);
    }

    public function testMagicMethodProperty()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame('woop', $presenter->testProperty);
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
            $this->assertSame("The method '$method' was not found on the presenter class '$class'.", $e->getMessage());
            $this->assertSame($method, $e->getMethod());
            $this->assertSame($class, $e->getClass());
            throw $e;
        }
    }

    public function testIsSet()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertTrue(isset($presenter->myProperty));
    }

    public function testToString()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame('hello there', (string) $presenter);
    }
}
