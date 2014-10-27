<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use McCool\LaravelAutoPresenter\Decorators\AtomDecorator;
use McCool\LaravelAutoPresenter\Decorators\CollectionDecorator;
use McCool\LaravelAutoPresenter\Decorators\PaginationDecorator;

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
                $app['events']->fire('content.rendering', [$view]);
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
        $this->registerAtomDecorator($this->app);
        $this->registerCollectionDecorator($this->app);
        $this->registerPaginatorDecorator($this->app);

        $this->registerPresenterDecorator($this->app);
    }

    /**
     * Register the atom decorator.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerAtomDecorator(Application $app)
    {
        $app->singleton('autopresenter.atom', function (Application $app) {
            return new AtomDecorator($app);
        });

        $app->alias('autopresenter.atom', 'McCool\LaravelAutoPresenter\Decorators\AtomDecorator');
    }

    /**
     * Register the collection decorator.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerCollectionDecorator(Application $app)
    {
        $app->singleton('autopresenter.collection', function (Application $app) {
            return new CollectionDecorator($app);
        });

        $app->alias('autopresenter.collection', 'McCool\LaravelAutoPresenter\Decorators\CollectionDecorator');
    }

    /**
     * Register the paginator decorator.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerPaginatorDecorator(Application $app)
    {
        $app->singleton('autopresenter.paginator', function (Application $app) {
            return new PaginatorDecorator($app);
        });

        $app->alias('autopresenter.paginator', 'McCool\LaravelAutoPresenter\Decorators\PaginatorDecorator');
    }

    /**
     * Register the presenter decorator.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerPresenterDecorator(Application $app)
    {
        $app->singleton('autopresenter', function (Application $app) {
            $atom = $app['autopresenter.atom'];
            $collection = $app['autopresenter.collection'];
            $pagination = $app['autopresenter.pagination'];

            return new PresenterDecorator($atom, $collection, $pagination);
        });

        $app->alias('autopresenter', 'McCool\LaravelAutoPresenter\PresenterDecorator');
    }
}
