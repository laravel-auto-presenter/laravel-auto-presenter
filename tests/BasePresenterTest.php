<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests;

use McCool\LaravelAutoPresenter\Exceptions\MethodNotFoundException;
use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;

class BasePresenterTest extends AbstractTestCase
{
    private $decoratedAtom;
    private $presenter;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->decoratedAtom = $app->make(DecoratedAtom::class);
        $this->presenter = (new DecoratedAtomPresenter())->setWrappedObject($this->decoratedAtom);
    }

    public function testResourceIsReturned()
    {
        $this->assertSame($this->decoratedAtom, $this->presenter->getWrappedObject());
    }

    public function testResourceAttributeDeference()
    {
        $this->assertSame(DecoratedAtomPresenter::class, $this->presenter->getPresenterClass());
    }

    public function testPresenterMethodDeference()
    {
        $this->assertSame('Primer', $this->presenter->favoriteMovie);
    }

    public function testResourcePropertyViaMagicMethod()
    {
        $this->assertSame('bazinga', $this->presenter->myProperty);
    }

    public function testMagicMethodProperty()
    {
        $this->assertSame('woop', $this->presenter->testProperty);
    }

    public function testResourceMethodNotFoundExceptionThrowsException()
    {
        $method = 'thisMethodDoesntExist';
        $class = DecoratedAtomPresenter::class;
        $this->expectException(MethodNotFoundException::class);
        $this->expectExceptionMessage("The method '$method' was not found on the presenter class '$class'.");

        try {
            $this->presenter->thisMethodDoesntExist();
        } catch (MethodNotFoundException $e) {
            $this->assertSame($method, $e->getMethod());
            $this->assertSame($class, $e->getClass());

            throw $e;
        }
    }

    public function testIsSet()
    {
        $this->assertTrue(isset($this->presenter->myProperty));
        $this->assertTrue(isset($this->presenter->favoriteMovie));
    }

    public function testToString()
    {
        $this->assertSame('hello there', (string) $this->presenter);
    }
}
