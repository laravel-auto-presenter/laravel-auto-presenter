<?php namespace McCool\Tests\Functional;

use Illuminate\Contracts\Foundation\Application;
use McCool\Tests\AbstractTestCase;
use McCool\Tests\Stubs\ModelStub;

class ViewDataTest extends AbstractTestCase
{
    protected function additionalSetup(Application $app)
    {
        $app['view']->addNamespace('stubs', realpath(__DIR__.'/stubs'));
    }

    public function testBasicSetup()
    {
        // make a new dummy model
        $model = new ModelStub();

        // set the foo attribute
        $model->foo = 'hi';

        // create a new view, with the model
        $view = $this->app['view']->make('stubs::test')->withModel($model);

        // check nothing has been modified yet
        $this->assertInstanceOf('McCool\Tests\Stubs\ModelStub', $view->model);
        $this->assertSame('hi', $view->model->foo);

        // render the view
        $view->render();

        // check that the model was decorated
        $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->model);
        $this->assertSame('hi there', $view->model->foo);

        // render the view again
        $view->render();

        // check everything is still the same
        $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->model);
        $this->assertSame('hi there', $view->model->foo);
    }
}
