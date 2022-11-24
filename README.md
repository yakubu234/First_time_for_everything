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

# Credits due where credits due…

Thanks to [MaxiBuy](https://www.maxibuy.co/) for giving me the opportunity to showcase my skill [Github Link](https://github.com/yakubu234/First_time_for_everything.git).

```

```
