<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class LaravelAutoPresenterServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->app->singleton(
            'McCool\LaravelAutoPresenter\PresenterDecorator',
            'McCool\LaravelAutoPresenter\PresenterDecorator'
        );
    }

    public function boot()
    {
        $this->package('mccool/laravel-auto-presenter');

        $app = $this->app;

        // every time a view is rendered, fire a new event
        $app['view']->composer('*', function ($view) use ($app) {
            if ($view instanceof View) {
                $app['events']->fire('content.rendering', array($view));
            }
        });

        // every time that event fires, decorate the bound data
        $app['events']->listen('content.rendering', function ($view) use ($app) {
            $sharedData = $view->getFactory()->getShared();
            $viewData = array_merge($sharedData, $view->getData());

            if (!$viewData) {
                return;
            }

            $decorator = $app->make('McCool\LaravelAutoPresenter\PresenterDecorator');

            foreach ($viewData as $key => $value) {
                $view[$key] = $decorator->decorate($value);
            }
        });
    }
}
