Laravel Auto Presenter 7
========================

This package automatically decorates objects bound to views during the view render process.

![Banner](https://user-images.githubusercontent.com/2829600/71563899-63b94e80-2a8f-11ea-9c5a-18e5484405a9.png)

<p align="center">
<a href="https://github.styleci.io/repos/12034701"><img src="https://github.styleci.io/repos/12034701/shield" alt="StyleCI Status"></img></a>
<a href="https://github.com/laravel-auto-presenter/laravel-auto-presenter/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/workflow/status/laravel-auto-presenter/laravel-auto-presenter/Tests?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/laravel-auto-presenter/laravel-auto-presenter/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/laravel-auto-presenter/laravel-auto-presenter?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/laravel-auto-presenter/laravel-auto-presenter"><img src="https://img.shields.io/scrutinizer/g/laravel-auto-presenter/laravel-auto-presenter?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/laravel-auto-presenter/laravel-auto-presenter/releases"><img src="https://img.shields.io/github/release/laravel-auto-presenter/laravel-auto-presenter?style=flat-square" alt="Latest Version"></img></a>
</p>


## Features

- Automatically decorate objects bound to views
- Automatically decorate objects within paginator instances
- Automatically decorate objects within arrays and collections


## Installing

Laravel Auto Presenter requires [PHP](https://php.net) 7.1-7.4 and supports Laravel 5.5-7. If you need support for older Laravel or PHP versions, please choose an older version of Laravel Auto Presenter.

| Auto Presenter | L5.1               | L5.2               | L5.3               | L5.4               | L5.5               | L5.6               | L5.7               | L5.8               | L6                 | L7                 |
|----------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| 4.3            | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 5.0            | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 6.2            | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                |
| 7.4            | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require mccool/laravel-auto-presenter
```

Once installed, if you are not using automatic package discovery, then you need to register the `McCool\LaravelAutoPresenter\AutoPresenterServiceProvider` service provider in your `config/app.php`.

You can also optionally alias our facade:

```php
        'AutoPresenter' => McCool\LaravelAutoPresenter\Facades\AutoPresenter::class,
```


## Upgrading

### Version 6 to 7

In Laravel Auto Presenter 7, note that:

* Laravel 5.5-7 are supported now. Use V6 if you need L5.1-5.4.
* Our new minimum PHP version requirement is 7.1.3, up from 7.0.0.

### Version 5 to 6

Going from Laravel Auto Presenter 5, to 6, note that:

* We have a new `Decoratable` interface to determine if relations can be decorated. While this is not a BC break, since `HasPresenter` extends it, it is definitely worth noting.
* v6 supports Laravel 5.5 now, without dropping support for 5.1+.
* Our new minimum PHP version requirement is 7.0.0, up from 5.5.9.

### Version 4 to 5

If you're upgrading from Laravel Auto Presenter 4, to 5, note that:

* The `BasePresenter` no longer has a constructor, so you cannot call `parent::__construct($resource)`.
* The model is now injected using the `setWrappedObject` method, inherited from the `BasePresenter`.
* V5 now supports Laravel 5.4 as well as 5.1, 5.2, and 5.3.


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

If an object is a relation of another object and it isn't being decorated in the view, you might not have added the `Decoratable` interface to the other object. To fix this, add the `Decoratable` interface to the other object.


## Security

If you discover a security vulnerability within this package, please send an email to Graham Campbell at graham@alt-three.com. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/laravel-auto-presenter/laravel-auto-presenter/security/policy).


## License

Laravel Auto Presenter is licensed under [The MIT License (MIT)](LICENSE).


## For Enterprise

Available as part of the Tidelift Subscription

The maintainers of `mccool/laravel-auto-presenter` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-mccool-laravel-auto-presenter?utm_source=packagist-mccool-laravel-auto-presenter&utm_medium=referral&utm_campaign=enterprise&utm_term=repo)
