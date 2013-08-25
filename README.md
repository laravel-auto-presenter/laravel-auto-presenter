# Laravel Auto Presenter

This package automatically decorates objects bound to views during the view render process.

## Quick-install

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "*"
    }
}
```

<a name="requirements"/>
## Requirements

- any flavor of PHP 5.3+ should do
- Laravel 4.x
- [optional] PHPUnit to run the tests

<a name="features"/>
## Features

- automatically decorate objects bound to views
- automatically decorate objects within paginator instances
- automatically decorate objects within collection objects
- automatically decorate Eloquent relationships that are loaded at the time that the view begins being rendered

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
        "mccool/laravel-auto-presenter": "*"
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

Here, the automatic presenter decorator is injecting the Post model that is to be decorated. We just need to add 1 line to the Post class.

```php
public $presenter = 'Example\Blog\PostPresenter';
```

To make it perfectly clear, here's the updated Post class..

```php
<?php namespace Example\Blog;

class Post extends \Eloquent
{
    protected $table    = 'posts';
    protected $fillable = array('author_id', 'title', 'content', 'published_at');

    public $presenter = 'Example\Blog\PostPresenter';

    public function author()
    {
        return $this->belongsTo('Example\Accounts\User', 'author_id');
    }
}
```

Now, with no additional changes our view will show the date in the desired format.

## Troubleshooting

If an object isn't being decorated correctly in the view then there's a good chance that it's simply not in existence when the view begins to render. For example, lazily-loaded relationships won't be decorated. You can fix this by eager-loading them instead. Auth::user() will never be decorated. I prefer to bind $currentUser to my views, anyway.
