<?php namespace McCool\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class ModelStub extends Model implements HasPresenter
{
    public function getPresenterClass()
    {
        return ModelPresenter::class;
    }
}
