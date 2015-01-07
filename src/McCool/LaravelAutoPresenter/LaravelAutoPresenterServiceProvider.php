<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Support\ServiceProvider;

class LaravelAutoPresenterServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        // register as a singleton because we don't need to be instantiating a new one all the time
        $this->app->singleton('McCool\LaravelAutoPresenter\PresenterDecorator', 'McCool\LaravelAutoPresenter\PresenterDecorator');
    }

    public function boot()
    {
        $this->package('mccool/laravel-auto-presenter');

        // every time a view is rendered, fire a new event
        $this->app['view']->composer('*', function($view) {
            if ($view instanceOf \Illuminate\View\View) {
                $this->app['events']->fire('content.rendering', array($view));
            }
        });

        // every time that event fires, decorate the bound data
        $app = $this->app;

        $this->app['events']->listen('content.rendering', function($view) use ($app) {
            $sharedData = $view->getFactory()->getShared();
            $viewData = array_merge($sharedData, $view->getData());

            if ( ! $viewData) {
                return;
            }

            $decorator = $app->make('McCool\LaravelAutoPresenter\PresenterDecorator');

            foreach ($viewData as $key => $value) {
                $view[$key] = $decorator->decorate($value);
            }
        });
    }
}
