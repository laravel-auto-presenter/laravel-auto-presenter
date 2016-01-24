<?php

/*
 * This file is part of Laravel Auto Presenter.
 *
 * (c) Shawn McCool <shawn@heybigname.com>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace McCool\LaravelAutoPresenter;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use McCool\LaravelAutoPresenter\Decorators\ArrayDecorator;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator;

class AutoPresenterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupEventFiring($this->app);
        $this->setupEventListening($this->app);
    }

    /**
     * Setup the event firing.
     *
     * Every time a view is rendered, fire a new event.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupEventFiring(Container $app)
    {
        $app['view']->composer('*', function ($view) use ($app) {
            if ($view instanceof View) {
                $app['events']->fire('content.rendering', [$view]);
            }
        });
    }

    /**
     * Setup the event listening.
     *
     * Every time the event fires, decorate the bound data.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    protected function setupEventListening(Container $app)
    {
        $app['events']->listen('content.rendering', function (View $view) use ($app) {
            if ($viewData = array_merge($view->getFactory()->getShared(), $view->getData())) {
                $decorator = $app['autopresenter'];
                foreach ($viewData as $key => $value) {
                    $view[$key] = $decorator->decorate($value);
                }
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAutoPresenter($this->app);
    }

    /**
     * Register the presenter decorator.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    public function registerAutoPresenter(Container $app)
    {
        $app->singleton('autopresenter', function (Container $app) {
            $autoPresenter = new AutoPresenter();

            $autoPresenter->register(new AtomDecorator($autoPresenter, $app));
            $autoPresenter->register(new ArrayDecorator($autoPresenter));
            $autoPresenter->register(new PaginatorDecorator($autoPresenter));

            return $autoPresenter;
        });

        $app->alias('autopresenter', AutoPresenter::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'autopresenter',
        ];
    }
}
