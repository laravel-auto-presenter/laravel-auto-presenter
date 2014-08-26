# Laravel Auto Presenter 2

[![Build Status](https://travis-ci.org/ShawnMcCool/laravel-auto-presenter.svg?branch=2.2)](https://travis-ci.org/ShawnMcCool/laravel-auto-presenter)

This package automatically decorates objects bound to views during the view render process.

## Upgrading From Version 1 To 2

* Version 2 is now Laravel 4.2 only.
* The PresenterInterface was added as the method for determining the correct presenter class. Read more on this in the instructions below.

## Quick-Install

**For Laravel 4.1**

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "~1.2"
    }
}
```

**For Laravel 4.2**

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "~2.2"
    }
}
```

<a name="requirements"/>
## Requirements

- PHP 5.4+
- Laravel 4.2.x
- [optional] PHPUnit to run the tests

<a name="features"/>
## Features

- Automatically decorate objects bound to views
- Automatically decorate objects within paginator instances
- Automatically decorate objects within collection objects
- Automatically decorate Eloquent relationships that are loaded at the time that the view begins being rendered

<a name="install-laravel-package-installer"/>
## Installing With The Laravel 4 Package Installer

1. Install the [Laravel 4 Package Installer](https://github.com/rtablada/package-installer)
2. Run `php artisan package:install mccool/laravel-auto-presenter`

<a name="install-composer"/>
## Installing With Composer

Install this package with [Composer](http://getcomposer.org/).

Add the following "require" to your `composer.json` file and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "~2.2"
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
    protected $table = 'posts';
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
    protected $table = 'posts';
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

## Troubleshooting

If an object isn't being decorated correctly in the view then there's a good chance that it's simply not in existence when the view begins to render. For example, lazily-loaded relationships won't be decorated. You can fix this by eager-loading them instead. Auth::user() will never be decorated. I prefer to bind $currentUser to my views, anyway.

## License

MIT
