<?php namespace McCool\Tests;

use McCool\LaravelAutoPresenter\BasePresenter;
use Mockery as m;

class BasePresenterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testResourceAttributeDeference()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new \McCool\Tests\Stubs\DecoratedAtomPresenter($atom);

        $this->assertEquals('McCool\Tests\Stubs\DecoratedAtomPresenter', $presenter->presenter);
    }

    public function testPresenterMethodDeference()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new \McCool\Tests\Stubs\DecoratedAtomPresenter($atom);

        $this->assertEquals('Primer', $presenter->favorite_movie);
    }

    /**
    * @covers presenter::thisMethodDoesntExist
    * @expectedException McCool\LaravelAutoPresenter\ResourceMethodNotFoundException
    */
    public function testResourceMethodNotFoundThrowsException()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new \McCool\Tests\Stubs\DecoratedAtomPresenter($atom);

        $presenter->thisMethodDoesntExist();
    }

    private function getDecoratedAtom()
    {
        return new \McCool\Tests\Stubs\DecoratedAtom;
    }
}