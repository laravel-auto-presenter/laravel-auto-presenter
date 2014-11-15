Laravel Auto Presenter 3
========================

[![Build Status](https://img.shields.io/travis/ShawnMcCool/laravel-auto-presenter/master.svg?style=flat-square)](https://travis-ci.org/ShawnMcCool/laravel-auto-presenter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This package automatically decorates objects bound to views during the view render process.

<a name="features"/>
## Features

- Automatically decorate objects bound to views
- Automatically decorate objects within paginator instances
- Automatically decorate objects within collection objects

<a name="requirements"/>
## Requirements

Version 3

- PHP 5.5+
- Laravel 5.x
- [optional] PHPUnit to run the tests

Version 2

- PHP 5.4+
- Laravel 4.2.x
- [optional] PHPUnit to run the tests

Version 1

- PHP 5.3+
- Laravel 4.1.x
- [optional] PHPUnit to run the tests

<a name="install-composer"/>
## Installing

You should install this package with [Composer](http://getcomposer.org/).

Add the following "require" to your `composer.json` file and run the `composer install` command to install it.

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

**For Laravel 5**

```json
{
    "require": {
        "mccool/laravel-auto-presenter": "~3.0@beta"
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
use Example\Accounts\User;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent
{
    protected $table = 'posts';
    protected $fillable = ['author_id', 'title', 'content', 'published_at'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
```

Also, we'll need a controller..

```php
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class PostsController extends Controller
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
use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;

class PostPresenter extends BasePresenter
{
    public function __construct(Post $post)
    {
        $this->wrappedObject = $post;
    }

    public function published_at()
    {
        $published = $this->wrappedObject->published_at;
        return Carbon::createFromFormat('Y-m-d H:i:s', $published)
            ->toFormattedDateString();
    }
}
```

Here, the automatic presenter decorator is injecting the Post model that is to be decorated. We need the post class to implement the interface.

```php
use Example\Accounts\User;
use Example\Blog\PostPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent implements HasPresenter
{
    protected $table = 'posts';
    protected $fillable = ['author_id', 'title', 'content', 'published_at'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getPresenterClass()
    {
        return PostPresenter::class;
    }
}
```

Now, with no additional changes our view will show the date in the desired format.

## Troubleshooting

If an object isn't being decorated correctly in the view then there's a good chance that it's simply not in existence when the view begins to render. For example, lazily-loaded relationships won't be decorated. You can fix this by eager-loading them instead. Auth::user() will never be decorated. I prefer to bind $currentUser to my views, anyway.

## License

MIT
