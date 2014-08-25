<?php namespace McCool\Tests;

use McCool\Tests\Stubs\DecoratedAtomPresenter;
use McCool\Tests\Stubs\DecoratedAtomFieldsPresenter;
use Mockery as m;

class BasePresenterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

	public function testResourceIsReturned()
	{
		$atom = $this->getDecoratedAtom();
		$presenter = new DecoratedAtomPresenter($atom);

		$this->assertEquals($atom, $presenter->getResource());
	}

	public function testFieldsAreReturned()
	{
		$atom = $this->getDecoratedAtom();
		$presenter = new DecoratedAtomFieldsPresenter($atom);

		$this->assertEquals(['name', 'address'], $presenter->getFields());
	}

	public function testFieldsAreAccessible()
	{
		$atom = $this->getDecoratedAtom();
		$presenter = new DecoratedAtomFieldsPresenter($atom);

		$this->assertTrue($presenter->accessible('name'));
		$this->assertFalse($presenter->accessible('somekeythatdoesntexist'));
	}

	public function testFieldsAreAccessibleWithEmptyFieldsArray()
	{
		$atom = $this->getDecoratedAtom();
		$presenter = new DecoratedAtomPresenter($atom);

		$this->assertTrue($presenter->accessible('name'));
	}

    public function testResourceAttributeDeference()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new DecoratedAtomPresenter($atom);

        $this->assertEquals('McCool\Tests\Stubs\DecoratedAtomPresenter', $presenter->getPresenter());
    }

    public function testPresenterMethodDeference()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new DecoratedAtomPresenter($atom);

        $this->assertEquals('Primer', $presenter->favorite_movie);
    }

    /**
    * @covers presenter::thisMethodDoesntExist
    * @expectedException McCool\LaravelAutoPresenter\ResourceMethodNotFoundException
    */
    public function testResourceMethodNotFoundThrowsException()
    {
        $atom = $this->getDecoratedAtom();

        $presenter = new DecoratedAtomPresenter($atom);

        $presenter->thisMethodDoesntExist();
    }

    private function getDecoratedAtom()
    {
        return new \McCool\Tests\Stubs\DecoratedAtom;
    }
}