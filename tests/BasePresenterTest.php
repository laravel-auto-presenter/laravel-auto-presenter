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

namespace McCool\Tests;

use McCool\LaravelAutoPresenter\Exceptions\MethodNotFound;
use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;

class BasePresenterTest extends AbstractTestCase
{
    private $decoratedAtom;

    /**
     * @before
     */
    public function setUpProperties()
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
        $this->assertTrue(isset($presenter->favoriteMovie));
    }

    public function testToString()
    {
        $presenter = new DecoratedAtomPresenter($this->decoratedAtom);
        $this->assertSame('hello there', (string) $presenter);
    }
}
