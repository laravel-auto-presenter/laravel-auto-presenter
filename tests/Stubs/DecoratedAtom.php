<?php namespace McCool\Tests\Stubs;

use McCool\LaravelAutoPresenter\HasPresenter;

class DecoratedAtom implements HasPresenter
{
    public $myProperty = "bazinga";

    /**
     * Get the presenter class.
     * @return string The class path to the presenter.
     */
    public function getPresenterClass()
    {
        return DecoratedAtomPresenter::class;
    }

    /**
     * __get properties should work too
     */
    public function __get($key)
    {
        if ($key == 'testProperty')
            return 'woop';
        else
            return $this->$key;
    }

    public function __isset($key)
    {
        return ( $key == 'testProperty' || !is_null($this->$key) );
    }
}
