<?php namespace McCool\Tests;

use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;
use McCool\Tests\Stubs\DecoratedAtomFieldsPresenter;
use Mockery as m;

class BasePresenterTest extends TestCase
{
	private $decoratedAtom;

	public function setUp()
	{
		$this->decoratedAtom = new DecoratedAtom;
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
