<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DecoratedAtom implements HasPresenter
{
    public $myProperty = "bazinga";

    public function getPresenterClass()
    {
        return DecoratedAtomPresenter::class;
    }

    public function __get($key)
    {
        if ($key == 'testProperty') {
            return 'woop';
        }

        return $this->$key;
    }

    public function __isset($key)
    {
        return (!is_null(@$this->$key));
    }
}
