[![Build Status](https://travis-ci.org/ShawnMcCool/laravel-auto-presenter.svg?branch=master)](https://travis-ci.org/ShawnMcCool/laravel-auto-presenter)

# Laravel Auto Presenter 3.0.0

This package automatically decorates objects bound to views during the view render process.

## Upgrading from version 2 to 3

Version 3 now properly supports and requires Laravel 4.2.x. This was recently causing builds to fail.

## Upgrading from version 1 to 2

* Version 2 is now Laravel 4.2+ only. It is not compatible with Laravel 4.1 and below.
* The PresenterInterface was added as the method for determining the correct presenter class. Read more on this in the instructions below.

## Quick-install

**For Laravel Versions 4.1 and below**

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "1.*"
    }
}
```

**For Laravel Versions 4.2 and above**

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "2.*"
    }
}
```

<a name="requirements"/>
## Requirements

- any flavor of PHP 5.4+ should do
- Laravel 4.2.x
- [optional] PHPUnit to run the tests

<a name="features"/>
## Features

- automatically decorate objects bound to views
- automatically decorate objects within paginator instances
- automatically decorate objects within collection objects
- automatically decorate Eloquent relationships that are loaded at the time that the view begins being rendered
- restrict fields included as part of presentation layer

<a name="install-laravel-package-installer"/>
## Install with Laravel 4 Package Installer

1. Install [Laravel 4 Package Installer](https://github.com/rtablada/package-installer)
2. Run `php artisan package:install mccool/laravel-auto-presenter`

<a name="install-composer"/>
## Install with Composer

Install this package with [Composer](http://getcomposer.org/).

Add the following "require" to your `composer.json` file and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "3.0.*"
    }
}
```

Then, in config/app.php add this line to your 'providers' array.

```php
'McCool\LaravelAutoPresenter\LaravelAutoPresenterServiceProvider',
```

## Usage

To show how it's used, we'll pretend that we have an Eloquent Post model. It doesn't have to be Eloquent, it could be any kind of class. But, this is a normal situation. The Post model represents a blog post.

I'm using really basic code examples here, so just focus on how the auto-presenter is used and ignore the rest.

```php
<?php namespace Example\Blog;

class Post extends \Eloquent
{
    protected $table    = 'posts';
    protected $fillable = array('author_id', 'title', 'content', 'published_at');

    public function author()
    {
        return $this->belongsTo('Example\Accounts\User', 'author_id');
    }
}
```

Also, we'll need a controller..

```php
class PostsController extends \Controller
{
    public function getIndex()
    {
        $posts = Post::all();

        return View::make('posts.index', compact('posts'));
    }
}
```

and a view...

```twig
@foreach($posts as $post)
    <li>{{ $post->title }} - {{ $post->published_at }}</li>
@endforeach
```

In this example the published_at attribute is likely to be in the format: "Y-m-d H:i:s" or "2013-08-10 10:20:13". In the real world this is not what we want in our view. So, let's make a presenter that lets us change how the data from the Post class is rendered within the view.

```php
<?php namespace Example\Blog;

use McCool\LaravelAutoPresenter\BasePresenter;

class PostPresenter extends BasePresenter
{
    public function __construct(Post $post)
    {
        $this->resource = $post;
    }

    public function published_at()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->published_at, 'Europe/Berlin')
                     ->toFormattedDateString();
    }
}
```

Here, the automatic presenter decorator is injecting the Post model that is to be decorated. We need the post class to implement the interface.

```php
<?php namespace Example\Blog;

use McCool\LaravelAutoPresenter\PresenterInterface;

class Post extends \Eloquent implements PresenterInterface
{
    protected $table    = 'posts';
    protected $fillable = array('author_id', 'title', 'content', 'published_at');

    public function author()
    {
        return $this->belongsTo('Example\Accounts\User', 'author_id');
    }

    public function getPresenter()
    {
        return 'Example\Blog\PostPresenter';
    }
}
```

Now, with no additional changes our view will show the date in the desired format.

## Field restriction

You can restrict the fields available on the presenter to be a smaller, limited set that was originally available on the resource object your presenter is managing. To do this, define the $exposedFields protect variable on your presenter class:

```php
class PostPresenter extends BasePresenter
{
	protected $exposedFields = [
		'title',
		'createdAt'
	];
}
```

The exposed fields act as a white list array. If you do not provide any fields, then it will be ignored. If however, you do - then only fields that defined within the $exposedFields array and exist on the resource will be returned as part of the presenter.

## Troubleshooting

If an object isn't being decorated correctly in the view then there's a good chance that it's simply not in existence when the view begins to render. For example, lazily-loaded relationships won't be decorated. You can fix this by eager-loading them instead. Auth::user() will never be decorated. I prefer to bind $currentUser to my views, anyway.

## License

MIT
