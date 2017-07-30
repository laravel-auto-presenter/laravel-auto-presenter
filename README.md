Laravel Auto Presenter 6
========================

[![StyleCI Status](https://styleci.io/repos/12034701/shield)](https://styleci.io/repos/12034701)
[![Build Status](https://img.shields.io/travis/laravel-auto-presenter/laravel-auto-presenter/master.svg?style=flat-square)](https://travis-ci.org/laravel-auto-presenter/laravel-auto-presenter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/mccool/laravel-auto-presenter.svg?style=flat-square)](https://packagist.org/packages/mccool/laravel-auto-presenter)
[![Latest Version](https://img.shields.io/github/release/laravel-auto-presenter/laravel-auto-presenter.svg?style=flat-square)](https://github.com/laravel-auto-presenter/laravel-auto-presenter/releases)

This package automatically decorates objects bound to views during the view render process.


## Features

- Automatically decorate objects bound to views
- Automatically decorate objects within paginator instances
- Automatically decorate objects within arrays and collections


## Upgrading

If you're upgrading from Laravel Auto Presenter 4, to 5, note that:

* The `BasePresenter` no longer has a constructor, so you cannot call `parent::__construct($resource)`.
* The model is now injected using the `setWrappedObject` method, inherited from the `BasePresenter`.
* V5 now supports Laravel 5.4 as well as 5.1, 5.2, and 5.3.

Going from Laravel Auto Presenter 5, to 6, note that:

* We have a new `Decoratable` interface to determine if relations can be decorated. While this is not a BC break, since `HasPresenter` extends it, it is defintely worth noting.
* v6 supports Laravel 5.5 now, without dropping support for 5.1+.
* Our new minimum PHP version requirement is 7.0.0, up from 5.5.9.


## Installing

Laravel Auto Presenter 6 requires [PHP](https://php.net) 7. This particular version supports Laravel 5.1, 5.2, 5.3, 5.4, or 5.5 only. If you need support for older PHP versions, please see Laravel Auto Presenter 5.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require mccool/laravel-auto-presenter
```

Once installed, you need to register the `McCool\LaravelAutoPresenter\AutoPresenterServiceProvider` service provider in your `config/app.php`, and optionally alias our facade:

```php
        'AutoPresenter' => McCool\LaravelAutoPresenter\Facades\AutoPresenter::class,
```


## Usage

To show how it's used, we'll pretend that we have an Eloquent Post model. It doesn't have to be Eloquent, it could be any kind of class. But, this is a normal situation. The Post model represents a blog post.

I'm using really basic code examples here, so just focus on how the auto-presenter is used and ignore the rest.

```php
use Example\Accounts\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
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
use Example\Accounts\Post;
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
use Example\Accounts\Post;
use McCool\LaravelAutoPresenter\BasePresenter;

class PostPresenter extends BasePresenter
{
    public function published_at()
    {
        $published = $this->wrappedObject->published_at;

        return Carbon::createFromFormat('Y-m-d H:i:s', $published)
            ->toFormattedDateString();
    }
}
```

*Note that the model is injected by calling the `setWrappedObject` method, inherited from `BasePresenter`.*

We need the post class to implement the interface.

```php
use Example\Accounts\User;
use Example\Blog\PostPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements HasPresenter
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

**The `Decoratable` interface is used to allow the model's relations to be decorated, and the `HasPresenter` interface (which extends that one) is used to have the model itself decorated.**


## Troubleshooting

If an object isn't being decorated correctly in the view then there's a good chance that it's simply not in existence when the view begins to render. For example, lazily-loaded relationships won't be decorated. You can fix this by eager-loading them instead. Auth::user() will never be decorated. I prefer to bind $currentUser to my views, anyway.


## Security

If you discover a security vulnerability within this package, please send an e-mail to Graham Campbell at graham@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Laravel Auto Presenter is licensed under [The MIT License (MIT)](LICENSE).
