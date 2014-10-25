<?php namespace McCool\Tests;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;
use McCool\LaravelAutoPresenter\PresenterDecorator;
use McCool\Tests\Stubs\DecoratedAtom;
use McCool\Tests\Stubs\DecoratedAtomPresenter;
use McCool\Tests\Stubs\UndecoratedAtom;
use McCool\Tests\Stubs\WronglyDecoratedAtom;

class PresenterDecoratorTest extends TestCase
{
    private $decorator;

    public function setUp()
    {
        $atom = new AtomDecorator();
        $collection = new CollectionDecorator();
        $paginator = new PaginatorDecorator();

        $this->decorator = new PresenterDecorator($atom, $collection, $paginator);
    }

    public function testWontDecorateOtherObjects()
    {
        $atom = new UndecoratedAtom();
        $decoratedAtom = $this->decorator->decorate($atom);

        $this->assertInstanceOf(UndecoratedAtom::class, $decoratedAtom);
    }

    public function testDecoratesAtom()
    {
        $atom = $this->getDecoratedAtom();
        $decoratedAtom = $this->decorator->decorate($atom);

        $this->assertInstanceOf(DecoratedAtomPresenter::class, $decoratedAtom);
    }

    public function testDecoratesPaginator()
    {
        $paginator = $this->getFilledPaginator();
        $decoratedPaginator = $this->decorator->decorate($paginator);

        $this->assertCount(5, $decoratedPaginator);

        foreach ($decoratedPaginator as $decoratedAtom) {
            $this->assertInstanceOf(DecoratedAtomPresenter::class, $decoratedAtom);
        }
    }

    public function testDecorateCollection()
    {
        $collection = $this->getFilledCollection();
        $decoratedCollection = $this->decorator->decorate($collection);

        $this->assertCount(5, $decoratedCollection);

        foreach ($decoratedCollection as $decoratedAtom) {
            $this->assertInstanceOf(DecoratedAtomPresenter::class, $decoratedAtom);
        }
    }

    /**
     * @covers decorator::decorate
     * @expectedException \McCool\LaravelAutoPresenter\PresenterNotFound
     */
    public function testWronglyDecoratedClassThrowsException()
    {
        $atom = new WronglyDecoratedAtom();
        $this->decorator->decorate($atom);
    }

    private function getDecoratedAtom()
    {
        return new DecoratedAtom();
    }

    private function getFilledPaginator()
    {
        $items = array();

        foreach (range(1, 5) as $i) {
            $items[] = $this->getDecoratedAtom();
        }

        return new Paginator($items, 5);
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
