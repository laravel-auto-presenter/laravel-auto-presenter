<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class LaravelAutoPresenterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('mccool/laravel-auto-presenter');

        $this->setupEventFiring($this->app);

        $this->setupEventListening($this->app);
    }

    /**
     * Setup the event firing.
     *
     * Every time a view is rendered, fire a new event.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function setupEventFiring(Application $app)
    {
        $app['view']->composer('*', function ($view) use ($app) {
            if ($view instanceof View) {
                $app['events']->fire('content.rendering', array($view));
            }
        });
    }

    /**
     * Setup the event listening.
     *
     * Every time the event fires, decorate the bound data.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function setupEventListening(Application $app)
    {
        $app['events']->listen('content.rendering', function ($view) use ($app) {
            if ($viewData = array_merge($view->getFactory()->getShared(), $view->getData())) {
                $decorator = $app->make('McCool\LaravelAutoPresenter\PresenterDecorator');
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
        $this->app->singleton(
            'McCool\LaravelAutoPresenter\PresenterDecorator',
            'McCool\LaravelAutoPresenter\PresenterDecorator'
        );
    }
}
