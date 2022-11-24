## Cache in Laravel

The following documentation is based on home assessment for Maxibuy Backend Engineer Role<br> <br>
• Author: [Abiola Yakubu](https://github.com/yakubu234) <br>
• Twitter: [@grandrubicon](https://twitter.com/grandrubicon) <br>
• Linkedin: [Abiola Yakubu](https://www.linkedin.com/in/abiolayakubu/) <br>

## Usage <br>

Setup your coding environment <br>

```
git clone https://github.com/yakubu234/First_time_for_everything.git
cd First_time_for_everything
composer install
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
php artisan serve
```

## Database Setup <br>

We will be performing database tests which (obviously) needs to interact with the database. Make sure that your database credentials are up and running.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE
DB_USERNAME=YOUR_USERNAME
DB_PASSWORD=YOUR_PASSWORD_IF_ANY
```

Next up, we need to create the database which will be grabbed from the `DB_DATABASE` environment variable.

```
mysql;
create database YOUR_DATABASE;
exit;
```

## Cache in Laravel

<!-- In Laravel, the cache is the act of transparently storing data for future use in an attempt to make applications run faster.

You can definitely say that a cache looks like a session. You definitely use them in the same exact way, since you need to provide a key to store them.

Of course, there are differences. `Sessions` are used to store data between page requests while a `cache` is used to cache data per application. Therefore, you usually store stuff like database queries and API calls in your cache. -->

## Cache Configuration

Setup or Update the following variables in .env file

```
CACHE_DRIVER=redis
MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Installin Redis-server On Local system

run the following command to install redis-server which gives you a cli interface to confirm the authenticity of redis funtionalty on the local system, restart redis if not started automatically after installation, and check status. All simultaneously

```
sudo apt-get install redis-server
```

```
sudo systemctl restart redis
```

```
sudo systemctl status redis
```

## Accessing the Cache

There are a few different ways to access a cache. The first option is to use the Cache Façade.

```ruby
Cache::get(‘users’);
```

You can also get an instance from the container

```ruby
Route::get(‘users’), function(Illuminate\Contracts\Cache\Repository $cache) {
	return $cache->get(‘users’);
});
```

The last available method is to use the global `cache()` helper.

```ruby
$users = cache()->get(‘users’);
```

I personally prefer to use the Cache Façade, but don’t be bothered using any of the other available methods, since the output will be exactly the same.

## Available Methods

### get($key, $fallbackValue)

The `get()` method is used to pull values for any given key.

```ruby
Cache::get('users');
```

### pull($key, $fallbackValue)

The `pull()` method is exactly the same as the `get()` method, except it removes the cached value after retrieving it.

```ruby
Cache::pull('users');
```

### put($key, $value, $secondsOrExpiration)

The put method will set the value of the specified key for a given number of seconds.

```ruby
Cache::put(‘user’, ‘Code With Dary’, now()->addDay());
```

### add($key, $value)

The `add()` method is similar to the `put()` method but if the value already exists in the cache, it won’t set it. Keep in mind that it will also return a Boolean indicating whether or not the value was actually added.

```ruby
Cache::add(‘user’, 'Code With Dary');
```

### forever($key, $value)

The `forever()` method saves a value to the cache for a specific key. It’s similar to the `put()` method, except the value will never expire since you won’t set the expiration time.

```ruby
Cache::forever(user, 'Dary');
```

### has($key)

The `has()` method will check whether or not there’s a value at the provided key. It will return a true if the value exists, and false if the value does not exist.

```ruby
if(Cache::has(user)) {
    dd('Cache exists');
}
```

### increment($key, $amount)

The `increment()` method speaks for itself. It will increase the value in the cache. If there is no value given, it will treat the value as it was a 0.

```ruby
Cache::increment(‘user’, 1);
```

### decrement($key, $amount)

The `decrement()` method also speaks for itself. It will decrease the value in the cache. If there is no value given, it will treat the value as it was a 0.

```ruby
Cache::decrement('user', 1);
```

### forget($key)

The `forget()` method removes a previously set cache value.

```ruby
Cache::forget('user');
```

### flush()

The `flush()` method removes every cache value, even those set by the framework itself.

```ruby
Cache::flush();
```

## Example

I personally don’t like storing cache in the database so instead of showing you that, let’s create a simple example where we’re going to store 1000 posts inside the cache, so we don’t need to grab the values from the database every single time we try to find all posts.

Let’s start off by pulling in the authentication scaffolding.

```
composer require laravel/ui
php artisan ui vue –auth
npm install && npm run dev
```

For the posts, we need to create a model, factory, migration and resource controller.

```
php artisan make:model Post -fmr
```

Let’s define the posts migration in the `/database/migrations/create_posts_table.php` file:

```ruby
public function up()
{
    Schema::create('posts', function (Blueprint $table){
        $table->increments('id');
        $table->string('title');
        $table->longText('description');
        $table->timestamps();
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users');
    });
}
```

Let’s also set up our factory in the `/database/factory/PostFactory.php` file, which will be pretty simple because we only have a `user_id`, `title`, and `description` that needs to be set:

```ruby
public function definition()
{
    return [
        'user_id' => 1,
        'title' => $this->faker->sentence(),
        'description' => $this->faker->paragraph()
    ];
}
```

Before you can run tinker to run your factory, you got to make sure that you migrate your tables and create a new user, since `user_id` is already been set to 1.

The next step is to run our factory through tinker.

```
php artisan tinker
```

In here, we got to make sure that we call our model, chain the count method of and pass in an integer of the amount of rows we’d like to create, and chain the create method to finish it off.

```
App\Models\Post:factory()->count(1000)->create();
```

The output should be 1000 new rows inside your database. We got to make srue that we have an event and listener setup because it will fetch data from Laravels cache.

```
php artisan make:event PostCreated
php artisan make:listener PostCacheListener
```

Let’s change up the `handle()` method inside the `/app/Listeners/PostCacheListener.php` file.

```ruby
public function handle($event)
{
    Cache::forget('posts');

    Cache::forever('posts', Post::all());
}
```

We got to make sure that we remove the cache, even when it hasn’t been set. Then, we got to make sure that we create a new cache which will last forever. The values will be grabbed from the Post model.

We got to make sure that we hook our event into our model, which can easily be done with the property `$dispatchesEvents` in the `Post` model.

```ruby
protected $dispatchesEvents = [
    'created' => PostCreated::class
];
```

When using Events and Listeners, you got to make sure that you register them inside the `/app/Providers/EventServiceProvider.php` file, in the property `protected $listen`

```ruby
protected $listen = [
    Registered::class => [
        SendEmailVerificationNotification::class,
    ],
    PostCreated::class => [
        PostCacheListener::class
    ]
];
```

We’re almost done. We got to make sure that we dispatch the event, then get all posts and put it inside the cache inside our `\app\Controllers\PostController.php` file.

```ruby
public function index()
{
    Event::dispatch(new PostCreated());

    $posts = cache('posts', function () {
        return Post::get();
    });

    return view('index', $posts);
}
```

The last step if defining the `/blog` endpoint inside the `/routes/web.php` file and creating a new folder inside the `/resources/views/` folder, called `/blog`, and add a new file called `/index.blade.php` in there.

```ruby
Route::get('/', [PagesController::class, 'index']);
Route::get('/blog', [PostController::class, 'index']);
```

If we navigate to the browser and change our endpoint to `/blog`, the `/resources/views/blog/index.blade.php` file is being called, but the most important thing is the cache folder that has been created inside the `/storage/framework/cache/data` folder.

Right here, you’ll find a big JSON file which holds all posts inside that me as the user, has fetched.

# Credits due where credits due…

Thanks to [MaxiBuy](https://www.maxibuy.co/) for giving me the opportunity to showcase my skill [Github Link](https://github.com/yakubu234/First_time_for_everything.git).

```

```
