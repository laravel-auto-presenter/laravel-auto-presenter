<?php namespace McCool\LaravelAutoPresenter;

use Illuminate\Support\ServiceProvider;

use Event, View;

class LaravelAutoPresenterServiceProvider extends ServiceProvider
{
	protected $defer = false;

    public function register()
    {
        $this->app->singleton('McCool\LaravelAutoPresenter\PresenterDecorator', 'McCool\LaravelAutoPresenter\PresenterDecorator');
    }

	public function boot()
	{
		$this->package('mccool/laravel-auto-presenter');

        View::composer('*', function($view) {
            if ($view instanceOf \Illuminate\View\View) {
                Event::fire('content.rendering', array($view));
            }
        });

        Event::listen('content.rendering', function($view) {
            $viewData  = $view->getData();

            if ( ! $viewData) {
            	return;
            }

            $decorator = $this->app->make('McCool\LaravelAutoPresenter\PresenterDecorator');

            foreach ($viewData as $key => $value) {
                $view[$key] = $decorator->decorate($value);
            }
        });
	}
}