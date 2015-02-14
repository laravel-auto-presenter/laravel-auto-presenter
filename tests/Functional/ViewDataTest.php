<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\Tests\Functional;

use Exception;
use Illuminate\Support\Facades\Schema;
use McCool\Tests\AbstractTestCase;
use McCool\Tests\Stubs\ModelStub;

class ViewDataTest extends AbstractTestCase
{
    protected function additionalSetup($app)
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

    public function testSimpleCollection()
    {
        try {
            $this->setupAndSeedDatabase();
            $view = $this->app['view']->make('stubs::test')->withModels(ModelStub::all());
            $view->render();
            $this->assertCount(3, $view->models);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models[0]);
            $this->assertSame('hello there', $view->models[0]->foo);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models[1]);
            $this->assertSame('herro there', $view->models[1]->foo);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models[2]);
            $this->assertSame('herro there', $view->models[2]->foo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            Schema::drop('stubs');
        }
    }

    public function testGroupedCollection()
    {
        try {
            $this->setupAndSeedDatabase();
            $view = $this->app['view']->make('stubs::test')->withModels(ModelStub::all()->groupBy('foo'));
            $view->render();
            $this->assertCount(2, $view->models);
            $this->assertCount(1, $view->models['hello']);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models['hello'][0]);
            $this->assertSame('hello there', $view->models['hello'][0]->foo);
            $this->assertCount(2, $view->models['herro']);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models['herro'][0]);
            $this->assertSame('herro there', $view->models['herro'][0]->foo);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models['herro'][1]);
            $this->assertSame('herro there', $view->models['herro'][1]->foo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            Schema::drop('stubs');
        }
    }

    public function testArrayOfCollections()
    {
        try {
            $this->setupAndSeedDatabase();
            $models = ['firstModels' => ModelStub::all(), 'secondModels' => ModelStub::all()];
            $view = $this->app['view']->make('stubs::test')->withModels($models);
            $view->render();
            $this->assertCount(2, $view->models);
            $this->assertCount(3, $view->models['firstModels']);
            $this->assertCount(3, $view->models['secondModels']);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models['firstModels'][0]);
            $this->assertSame('hello there', $view->models['firstModels'][0]->foo);
            $this->assertInstanceOf('McCool\Tests\Stubs\ModelPresenter', $view->models['secondModels'][0]);
            $this->assertSame('hello there', $view->models['secondModels'][0]->foo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            Schema::drop('stubs');
        }
    }

    protected function setupAndSeedDatabase()
    {
        Schema::create('stubs', function ($table) {
            $table->string('foo');
            $table->timestamps();
        });

        $model = new ModelStub();
        $model->foo = 'hello';
        $model->save();

        $model = new ModelStub();
        $model->foo = 'herro';
        $model->save();

        $model = new ModelStub();
        $model->foo = 'herro';
        $model->save();
    }
}
