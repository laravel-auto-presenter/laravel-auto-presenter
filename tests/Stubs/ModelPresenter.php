<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\BasePresenter;

class ModelPresenter extends BasePresenter
{
    public function foo()
    {
        return $this->getWrappedObject()->foo.' there';
    }
}
