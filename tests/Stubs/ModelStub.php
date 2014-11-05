<?php

namespace McCool\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

class ModelStub extends Model implements HasPresenter
{
    protected $table = 'stubs';

    public function getPresenterClass()
    {
        return ModelPresenter::class;
    }
}
