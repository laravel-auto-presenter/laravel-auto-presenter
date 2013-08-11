<?php namespace McCool\Tests;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

use Mockery as m;

class PresenterDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testWontDecorateOtherObjects()
    {
        $atom = new \McCool\Tests\Stubs\UndecoratedAtom;
        $decorator = $this->getDecorator();

        $decoratedAtom = $decorator->decorate($atom);

        $this->assertInstanceOf('McCool\Tests\Stubs\UndecoratedAtom', $decoratedAtom);
    }

    public function testDecoratesAtom()
    {
        $atom = $this->getDecoratedAtom();
        $decorator = $this->getDecorator();

        $decoratedAtom = $decorator->decorate($atom);

        $this->assertInstanceOf('McCool\Tests\Stubs\DecoratedAtomPresenter', $decoratedAtom);
    }

    public function testDecoratesPaginator()
    {
        $paginator = $this->getFilledPaginator();
        $decorator = $this->getDecorator();

        $decoratedPaginator = $decorator->decorate($paginator);

        foreach ($decoratedPaginator as $decoratedAtom) {
            $this->assertInstanceOf('McCool\Tests\Stubs\DecoratedAtomPresenter', $decoratedAtom);
        }
    }

    public function testDecorateCollection()
    {
        $collection = $this->getFilledCollection();
        $decorator = $this->getDecorator();

        $decoratedCollection = $decorator->decorate($collection);

        foreach ($decoratedCollection as $decoratedAtom) {
            $this->assertInstanceOf('McCool\Tests\Stubs\DecoratedAtomPresenter', $decoratedAtom);
        }
    }

    /**
    * @covers decorator::decorate
    * @expectedException McCool\LaravelAutoPresenter\PresenterNotFoundException
    */
    public function testWronglyDecoratedAlassThrowsException()
    {
        $atom      = new \McCool\Tests\Stubs\WronglyDecoratedAtom;
        $decorator = $this->getDecorator();

        $decoratedAtom = $decorator->decorate($atom);
    }

    private function getDecorator()
    {
        return new \McCool\LaravelAutoPresenter\PresenterDecorator;
    }

    private function getDecoratedAtom()
    {
        return new \McCool\Tests\Stubs\DecoratedAtom;
    }

    private function getFilledPaginator()
    {
        $items = array();

        foreach (range(1, 5) as $i) {
            $items[] = $this->getDecoratedAtom();
        }

        $environment = m::mock('Illuminate\Pagination\Environment');
        $environment->shouldReceive('getCurrentPage')->andReturn(1);

        $paginator = new Paginator(
            $environment,
            $items,
            10,
            5
        );

        return $paginator->setupPaginationContext();
    }

    private function getFilledCollection()
    {
        $items = array();

        foreach (range(1, 5) as $i) {
            $items[] = $this->getDecoratedAtom();
        }

        return new Collection($items);
    }
}