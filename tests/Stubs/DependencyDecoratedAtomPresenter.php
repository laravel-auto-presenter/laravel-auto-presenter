<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\BasePresenter;

class DependencyDecoratedAtomPresenter extends BasePresenter
{
    protected $stub;

    public function __construct(InjectedStub $stub, $resource)
    {
        parent::__construct($resource);
        $this->stub = $stub;
    }

    public function favoriteNumber()
    {
        return $this->stub->number;
    }
}
