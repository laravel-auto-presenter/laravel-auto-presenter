<?php namespace McCool\Tests;

use McCool\LaravelAutoPresenter\BasePresenter;
use Mockery as m;

class BasePresenterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

}